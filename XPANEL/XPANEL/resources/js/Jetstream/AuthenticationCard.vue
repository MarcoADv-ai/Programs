<template>
    <div
        class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0"
    >
        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-100 shadow-md overflow-hidden rounded-md flex flex-col justify-around dark:bg-gray-800"
        >
            <div class="mx-auto w-60">
                <slot name="logo" />
            </div>
            <slot />
        </div>
    </div>
</template>
