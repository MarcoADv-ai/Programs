<script setup>
import { reactive, computed } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import { notify } from "notiwind";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetModalDialog from "@/Jetstream/DialogModal.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetButton from "@/Jetstream/Button.vue";
import Pagination from "@/Components/Pagination.vue";

const categories = computed(() => usePage().props.categories);
const wiki_posts = computed(() => usePage().props.wiki_posts);
const archive_posts = computed(() => usePage().props.archive_posts);

const category = reactive({
    name: "",
    slug: "",
});
const categoryEdit = reactive({
    name: "",
    slug: "",
});
const state = reactive({
    title: "",
    message: "",
    group: "",
    duration: 5000,
});

const modal = reactive({
    createCategory: false,
    editCategory: false,
});

function createCategory() {
    axios
        .post(route("admin.wiki.category.store"), category)
        .then((response) => {
            if (response.status == 200) {
                state.title = "Success";
                state.message = response.data.message;
                state.group = "success";
                notifyMe();
                router.reload();
            }
        });
}

function showCategory(id) {
    axios.get(route("admin.wiki.category.show", id)).then((response) => {
        if (response.status == 200) {
            categoryEdit.name = response.data.wiki_category.name;
            categoryEdit.slug = response.data.wiki_category.slug;
        }
    });
    modal.editCategory = true;
}

function updateCategory(id) {
    axios
        .put(route("admin.wiki.category.update", id), categoryEdit)
        .then((response) => {
            if (response.status == 200) {
                state.title = "Success";
                state.message = response.data.message;
                state.group = "success";
                notifyMe();
                router.reload();
            }
        });
}

function deleteCategory(id) {
    if (!confirm("Are you sure you want to delete this category?")) return;
    axios
        .delete(route("admin.wiki.category.destroy", id))
        .then((response) => {
            if (response.status == 200) {
                state.title = "Success";
                state.message = response.data.message;
                state.group = "success";
                notifyMe();
                router.reload();
            }
        })
        .catch((error) => {
            console.log(error);
        });
}

function delete_post(id) {
    if (!confirm("Are you sure you want to delete this post?")) return;
    axios
        .delete(route("admin.wiki.destroy", id))
        .then((response) => {
            if (response.status == 200) {
                state.title = "Success";
                state.message = response.data.message;
                state.group = "success";
                notifyMe();
                router.reload();
            }
        })
        .catch((error) => {
            console.log(error);
        });
}
function notifyMe() {
    notify(
        {
            group: state.group,
            title: state.title.toUpperCase(),
            text: state.message + " ",
        },
        state.duration
    );
}

function toggleArchive(id) {
    if (!confirm("Are you sure you want make this action?")) return;
    axios
        .put(route("admin.wiki.archive", id))
        .then((response) => {
            if (response.status == 200) {
                state.title = "Success";
                state.message = response.data.message;
                state.group = "success";
                notifyMe();
                router.reload();
            }
        })
        .catch((error) => {
            console.log(error);
        });
}
</script>
<template>
    <AdminLayout :title="'Wiki'">
        <div class="flex flex-wrap">
            <div class="w-full">
                <div
                    class="relative flex flex-col min-w-0 break-words w-full  shadow-md rounded bg-white px-3"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            <div
                                class="relative w-full  max-w-full flex justify-between items-center"
                            >
                                <h3
                                    class="font-semibold text-lg text-blueGray-700"
                                >
                                    Wiki Posts
                                </h3>
                                <Link :href="route('admin.wiki.create')">
                                    <JetButton>New Post</JetButton>
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="block w-full">
                        <table
                            class="items-center w-full bg-transparent border-collapse"
                        >
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        ID
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Title
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Categorie
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Description
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Slug
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Created at
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(
                                        wiki_post, index
                                    ) in wiki_posts.data"
                                >
                                    <th
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center"
                                    >
                                        <span
                                            class="ml-3 font-bold text-blueGray-600"
                                        >
                                            {{ wiki_post.id }}</span
                                        >
                                    </th>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{ wiki_post.title }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        <i
                                            class="fas fa-circle text-orange-500 mr-2"
                                        ></i>
                                        {{ wiki_post.category.name }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 overflow-hidden align-middle border-l-0 border-r-0 text-xs p-4"
                                    >
                                        <div class="flex items-center">
                                            {{ wiki_post.description }}
                                        </div>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 overflow-hidden align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        <div class="flex items-center">
                                            {{ wiki_post.slug }}
                                        </div>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 overflow-hidden align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        <div class="flex items-center">
                                            {{ wiki_post.created_at }}
                                        </div>
                                    </td>
                                    <td
                                        class="px-5 py-5 bg-white text-sm text-right"
                                    >
                                        <jet-dropdown>
                                            <template #trigger>
                                                <button
                                                    type="button"
                                                    class="inline-block text-gray-500 hover:text-gray-700 z-10"
                                                >
                                                    <svg
                                                        class="inline-block h-6 w-6 fill-current"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            d="M12 6a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-2 6a2 2 0 104 0 2 2 0 00-4 0z"
                                                        />
                                                    </svg>
                                                </button>
                                            </template>

                                            <template #content>
                                                <!-- Dropdown items -->
                                                <div
                                                    class="py-1"
                                                    role="menu"
                                                    aria-orientation="vertical"
                                                    aria-labelledby="options-menu"
                                                >
                                                    <JetDropdownLink
                                                        :href="
                                                            route('wiki.show', {
                                                                page: wiki_post.slug,
                                                                category:
                                                                    wiki_post
                                                                        .category
                                                                        .name,
                                                            })
                                                        "
                                                        class="flex justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                    >
                                                        <font-awesome-icon
                                                            icon="eye"
                                                            class="mr-2"
                                                        ></font-awesome-icon>
                                                        Show
                                                    </JetDropdownLink>
                                                    <JetDropdownLink
                                                        :href="
                                                            route(
                                                                'admin.wiki.edit',
                                                                {
                                                                    id: wiki_post.id,
                                                                }
                                                            )
                                                        "
                                                        class="flex justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                    >
                                                        <font-awesome-icon
                                                            icon="edit"
                                                            class="mr-2"
                                                        ></font-awesome-icon>
                                                        Edit
                                                    </JetDropdownLink>
                                                    <JetDropdownLink
                                                        @click="
                                                            delete_post(
                                                                wiki_post.id
                                                            )
                                                        "
                                                        class="flex justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                    >
                                                        <font-awesome-icon
                                                            icon="trash"
                                                            class="mr-2"
                                                        ></font-awesome-icon>
                                                        Destroy
                                                    </JetDropdownLink>
                                                    <JetDropdownLink
                                                        @click="
                                                            toggleArchive(
                                                                wiki_post.id
                                                            )
                                                        "
                                                        class="flex justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                    >
                                                        <font-awesome-icon
                                                            icon="archive"
                                                            class="mr-2"
                                                        ></font-awesome-icon>
                                                        Archive
                                                    </JetDropdownLink>
                                                </div>
                                            </template>
                                        </jet-dropdown>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="py-2">
                            <pagination
                                :links="wiki_posts.links"
                                class="text-gray-600"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap mt-4">
            <div class="w-full xl:w-8/12 mb-12 xl:mb-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full shadow-md rounded h-full px-3 "
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            <div
                                class="relative w-full max-w-full flex-grow flex-1"
                            >
                                <h3
                                    class="font-semibold text-lg text-blueGray-700"
                                >
                                    Archived Posts
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="block w-full h-full">
                        <div class="h-full">
                            <table
                                class="items-center w-full bg-transparent border-collapse"
                            >
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            ID
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Title
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Category
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Description
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Slug
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Created at
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        ></th>
                                    </tr>
                                </thead>
                            
                                <tbody>
                                    <tr
                                        v-for="post in archive_posts.data"
                                        v-if="archive_posts.data.length > 0"
                                    >
                                        <th
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center"
                                        >
                                            <span
                                                class="ml-3 font-bold text-blueGray-600"
                                            >
                                                {{ post.id }}</span
                                            >
                                        </th>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            {{ post.title }}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            <i
                                                class="fas fa-circle text-orange-500 mr-2"
                                            ></i>
                                            {{ post.category.name }}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            <i
                                                class="fas fa-circle text-orange-500 mr-2"
                                            ></i>
                                            {{ post.description }}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            <i
                                                class="fas fa-circle text-orange-500 mr-2"
                                            ></i>
                                            {{ post.slug }}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            <i
                                                class="fas fa-circle text-orange-500 mr-2"
                                            ></i>
                                            {{ post.created_at }}
                                        </td>

                                        <td
                                            class="px-5 py-5 bg-white text-sm text-right"
                                        >
                                            <jet-dropdown>
                                                <template #trigger>
                                                    <button
                                                        type="button"
                                                        class="inline-block text-gray-500 hover:text-gray-700 z-10"
                                                    >
                                                        <svg
                                                            class="inline-block h-6 w-6 fill-current"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                d="M12 6a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-2 6a2 2 0 104 0 2 2 0 00-4 0z"
                                                            />
                                                        </svg>
                                                    </button>
                                                </template>

                                                <template #content>
                                                    <!-- Dropdown items -->
                                                    <div
                                                        class="py-1"
                                                        role="category"
                                                        aria-orientation="vertical"
                                                        aria-labelledby="category"
                                                    >
                                                        <JetDropdownLink
                                                            @click="
                                                                toggleArchive(
                                                                    post.id
                                                                )
                                                            "
                                                            class="flex cursor-pointer justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                        >
                                                            <font-awesome-icon
                                                                icon="archive"
                                                                class="mr-2"
                                                            ></font-awesome-icon>
                                                            Unarchive
                                                        </JetDropdownLink>
                                                    </div>
                                                </template>
                                            </jet-dropdown>
                                        </td>
                                    </tr>
                                    <tr v-else>
                                        <td
                                            class="border-t-0 px-6 text-center align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                                            colspan="7"
                                        >
                                            <span
                                                class="ml-3 font-light text-gray-600"
                                            >
                                                No archive posts found
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <pagination
                                :links="archive_posts.links"
                            ></pagination>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full xl:w-4/12 pl-4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full  shadow-md rounded px-3 pb-2"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            <div
                                class="relative w-full max-w-full flex-grow flex-1"
                            >
                                <h3
                                    class="font-semibold text-lg text-blueGray-700"
                                >
                                    Post Categories
                                </h3>
                            </div>
                            <div
                                class="relative w-full  max-w-full flex-grow flex-1 text-right"
                            >
                                <JetButton
                                    class="text-white  text-xs font-bold uppercase px-2 py-1 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                                    type="button"
                                    @click="
                                        modal.createCategory =
                                            !modal.createCategory
                                    "
                                >
                                    New Category
                                </JetButton>
                            </div>
                        </div>
                    </div>
                    <div class="block w-full">
                        <div class="">
                            <table
                                class="items-center w-full bg-transparent border-collapse"
                            >
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            ID
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Name
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Slug
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        ></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="wiki_category in categories.data"
                                    >
                                        <th
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center"
                                        >
                                            <span
                                                class="ml-3 font-bold text-blueGray-600"
                                            >
                                                {{ wiki_category.id }}</span
                                            >
                                        </th>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            {{ wiki_category.name }}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            <i
                                                class="fas fa-circle text-orange-500 mr-2"
                                            ></i>
                                            {{ wiki_category.slug }}
                                        </td>

                                        <td
                                            class="px-5 py-5 bg-white text-sm text-right"
                                        >
                                            <jet-dropdown>
                                                <template #trigger>
                                                    <button
                                                        type="button"
                                                        class="inline-block text-gray-500 hover:text-gray-700 z-10"
                                                    >
                                                        <svg
                                                            class="inline-block h-6 w-6 fill-current"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                d="M12 6a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-2 6a2 2 0 104 0 2 2 0 00-4 0z"
                                                            />
                                                        </svg>
                                                    </button>
                                                </template>

                                                <template #content>
                                                    <!-- Dropdown items -->
                                                    <div
                                                        class="py-1"
                                                        role="category"
                                                        aria-orientation="vertical"
                                                        aria-labelledby="category"
                                                    >
                                                        <JetDropdownLink
                                                            @click="
                                                                showCategory(
                                                                    wiki_category.id
                                                                )
                                                            "
                                                            class="flex justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                        >
                                                            <font-awesome-icon
                                                                icon="edit"
                                                                class="mr-2"
                                                            ></font-awesome-icon>
                                                            Edit
                                                        </JetDropdownLink>
                                                        <JetDropdownLink
                                                            @click="
                                                                deleteCategory(
                                                                    wiki_category.id
                                                                )
                                                            "
                                                            class="flex justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                        >
                                                            <font-awesome-icon
                                                                icon="trash"
                                                                class="mr-2"
                                                            ></font-awesome-icon>
                                                            Destroy
                                                        </JetDropdownLink>
                                                    </div>
                                                </template>
                                            </jet-dropdown>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <pagination :links="categories.links"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>

    <jet-modal-dialog
        :maxWidth="'2xl'"
        :show="modal.createCategory"
        class="text-black"
    >
        <template #title>Category: {{ category.name }}</template>
        <template #content>
            <div>
                <label
                    for="slug-name"
                    class="block text-sm font-medium leading-6 text-gray-900"
                    >Category Name</label
                >
                <div class="mt-2">
                    <input
                        v-model="category.name"
                        type="text"
                        name="slug-name"
                        id="slug-name"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        placeholder="Example: Server Information"
                        aria-describedby="slug-name"
                    />
                </div>
            </div>
            <div class="mt-5">
                <label
                    for="slug"
                    class="block text-sm font-medium leading-6 text-gray-900"
                    >Slug</label
                >
                <div class="mt-2 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 px-3 text-gray-500 sm:text-sm"
                        >/</span
                    >
                    <input
                        v-model="category.slug"
                        type="text"
                        name="slug"
                        id="slug"
                        class="block w-full min-w-0 flex-1 rounded-none rounded-r-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        placeholder="slug name"
                    />
                </div>
            </div>
        </template>
        <template #footer>
            <jet-button class="mr-2" @click="createCategory()"
                >Create</jet-button
            >
            <jet-secondary-button
                @click="modal.createCategory = !modal.createCategory"
            >
                Close
            </jet-secondary-button>
        </template>
    </jet-modal-dialog>
    <jet-modal-dialog
        :maxWidth="'2xl'"
        :show="modal.editCategory"
        class="text-black"
    >
        <template #title>Category: {{ categoryEdit.name }}</template>
        <template #content>
            <div>
                <label
                    for="slug-name"
                    class="block text-sm font-medium leading-6 text-gray-900"
                    >Category Name</label
                >
                <div class="mt-2">
                    <input
                        v-model="categoryEdit.name"
                        type="text"
                        name="slug-name"
                        id="slug-name"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        placeholder="Example: Server Information"
                        aria-describedby="slug-name"
                    />
                </div>
            </div>
            <div class="mt-5">
                <label
                    for="slug"
                    class="block text-sm font-medium leading-6 text-gray-900"
                    >Slug</label
                >
                <div class="mt-2 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 px-3 text-gray-500 sm:text-sm"
                        >/</span
                    >
                    <input
                        v-model="categoryEdit.slug"
                        type="text"
                        name="slug"
                        id="slug"
                        class="block w-full min-w-0 flex-1 rounded-none rounded-r-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        placeholder="slug name"
                    />
                </div>
            </div>
        </template>
        <template #footer>
            <jet-button class="mr-2" @click="createCategory()"
                >Create</jet-button
            >
            <jet-secondary-button
                @click="modal.editCategory = !modal.editCategory"
            >
                Close
            </jet-secondary-button>
        </template>
    </jet-modal-dialog>
</template>
