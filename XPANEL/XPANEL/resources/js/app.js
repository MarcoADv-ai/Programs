import "./bootstrap";
import "../css/app.css";
import "../css/styles.css";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import CountryFlag from "vue-country-flag-next";
import VueCountdown from "@chenfengyuan/vue-countdown";
import { library } from "@fortawesome/fontawesome-svg-core";
import Notifications from "notiwind";
import CKEditor from "@ckeditor/ckeditor5-vue";
import {
    faFacebookSquare,
    faDiscord,
    faInstagram,
} from "@fortawesome/free-brands-svg-icons";
import {
    faHouseUser,
    faTrophy,
    faArrowRightFromBracket,
    faGear,
    faEye,
    faPenToSquare,
    faTrash,
    faBoxArchive,
    faCartShopping,
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { createPinia } from "pinia";
const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "~ xPanel ";

const icons = library.add(
    faFacebookSquare,
    faDiscord,
    faInstagram,
    faArrowRightFromBracket,
    faGear,
    faTrophy,
    faHouseUser,
    faEye,
    faPenToSquare,
    faTrash,
    faBoxArchive,
    faCartShopping
);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    progress: {
        color: "#20c5d4",
    },
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return (
            createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(icons)
                .use(ZiggyVue, Ziggy)
                .use(createPinia())
                // .use(CKEditor)
                .use(Notifications)
                .component(VueCountdown.name, VueCountdown)
                .component("font-awesome-icon", FontAwesomeIcon)
                .component("ck-editor", CKEditor.component)
                .use(CountryFlag)
                .mount(el)
        );
    },
});
