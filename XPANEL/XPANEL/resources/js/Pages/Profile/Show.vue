<template>
    <app-layout title="Profile">
        <div class="py-12 h-[90rem] text-black relative">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-36">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div>
                        <div
                            class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 bg-white"
                        >
                            <div
                                v-if="
                                    $page.props.jetstream
                                        .canUpdateProfileInformation
                                "
                            >
                                <update-profile-information-form
                                    :user="$page.props.user"
                                />

                                <jet-section-border />
                            </div>

                            <div v-if="$page.props.jetstream.canUpdatePassword">
                                <update-password-form class="mt-10 sm:mt-0" />

                                <jet-section-border />
                            </div>

                            <logout-other-browser-sessions-form
                                :sessions="sessions"
                                class="mt-10 sm:mt-0"
                            />

                            <template
                                v-if="
                                    $page.props.jetstream
                                        .hasAccountDeletionFeatures
                                "
                            >
                                <jet-section-border />

                                <delete-user-form class="mt-10 sm:mt-0" />
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";
import LogoutOtherBrowserSessionsForm from "@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue";
import TwoFactorAuthenticationForm from "@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";

export default defineComponent({
    props: ["sessions"],

    components: {
        AppLayout,
        DeleteUserForm,
        JetSectionBorder,
        LogoutOtherBrowserSessionsForm,
        TwoFactorAuthenticationForm,
        UpdatePasswordForm,
        UpdateProfileInformationForm,
    },
});
</script>
