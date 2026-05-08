<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { onMounted, ref } from "vue";

export default {
    layout: GuestLayout,
};
</script>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
const lang = ref(localStorage.getItem("lang"));

onMounted(() => {
    lang.value = localStorage.getItem("lang");
});
</script>

<template>
    <Head title="Home" />
    <section class="relative overflow-hidden h-screen">
        <video
            autoplay
            loop
            muted
            class="absolute w-auto min-w-full min-h-full object-cover dark:filter dark:brightness-50"
        >
            <source src="img/ez.mp4" type="video/mp4" />
            Your browser does not support the video tag.
        </video>
        <div class="container mx-auto h-full relative">
            <div
                class="mx-auto max-w-7xl px-6 pb-24 pt-10 sm:pb-32 lg:flex lg:px-8 lg:py-40 h-full flex items-center justify-center"
            >
                <div
                    class="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl lg:flex-shrink-0 lg:pt-8 flex items-center flex-col relative"
                >
                    <div
                        class="mx-auto mt-16 flex max-w-2xl sm:mt-24 lg:ml-10 lg:mr-0 lg:mt-0 lg:max-w-none lg:flex-none justify-center"
                    >
                        <div
                            class="max-w-3xl flex-none sm:max-w-5xl lg:max-w-none"
                        >
                            <div
                                class="-m-2 rounded-xl p-2 lg:-m-4 lg:rounded-2xl lg:p-4"
                            >
                                <img
                                    src="/img/logo.png"
                                    alt="App screenshot"
                                    width="348"
                                    height="178"
                                    class="w-[15rem] md:w-[20rem] rounded-md overflow-hidden"
                                />
                            </div>
                        </div>
                    </div>

                    <h1
                        v-if="lang === 'US'"
                        class="mt-10 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl dark:text-white"
                    >
                        Welcome to <span class="text-sky-400">xPanel</span>
                    </h1>
                    <h1
                        v-else-if="lang === 'ES'"
                        class="mt-10 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl"
                    >
                        Bienvenido a <span class="text-sky-400">xPanel</span>
                    </h1>
                    <h1
                        v-else-if="lang === 'PT'"
                        class="mt-10 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl"
                    >
                        Bem-vindo ao <span class="text-sky-400">xPanel</span>
                    </h1>
                    <h1
                        v-else-if="lang === 'FR'"
                        class="mt-10 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl"
                    >
                        Bienvenue à <span class="text-sky-400">xPanel</span>
                    </h1>
                    <p
                    class="mt-6 text-xl leading-8 text-black text-center dark:text-white"
                        v-if="lang === 'US'"
                    >
                        We are focus to provide the best and most stable
                        experiences possible. With recurring updates, new
                        content, we are sure you will love it!
                    </p>
                    <p
                        class="mt-6 text-xl leading-8 text-black text-center dark:text-white"
                        v-else-if="lang === 'ES'"
                    >
                        Nos enfocamos en brindar las mejores y más estables
                        experiencias posibles. Con actualizaciones periódicas,
                        nuevo contenido ¡Estamos seguros de que te encantará!
                    </p>
                    <p
                    class="mt-6 text-xl leading-8 text-black text-center dark:text-white"
                        v-else-if="lang === 'PT'"
                    >
                        Estamos focados em fornecer as melhores e mais estáveis
                        experiências possíveis. Com atualizações recorrentes,
                        novo conteúdo, temos certeza de que você vai adorar!
                    </p>
                    <p
                    class="mt-6 text-xl leading-8 text-black text-center dark:text-white"
                        v-else-if="lang === 'FR'"
                    >
                        Nous nous concentrons sur la fourniture des expériences
                        les meilleures et les plus stables possibles. Avec des
                        mises à jour récurrentes, de nouveaux contenus, nous
                        sommes sûrs que vous allez adorer!
                    </p>

                    <div class="mt-10 flex items-center gap-x-6 relative z-10">
                        <Link
                            :href="route('register')"
                            class="rounded-md bg-sky-500 px-3.5 py-2.5 text-lg font-semibold text-white shadow-sm transition duration-150 ease-in-out hover:bg-sky-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            <template v-if="lang == 'US'"
                                >Register Now</template
                            >
                            <template v-else-if="lang == 'ES'"
                                >Regístrate Ahora</template
                            >
                            <template v-else-if="lang == 'PT'"
                                >Registre-se Agora</template
                            >
                            <template v-else-if="lang == 'FR'"
                                >Inscrivez-vous maintenant</template
                            >
                        </Link>
                        <Link
                            :href="'/wiki/general/info'"
                            class="text-lg font-semibold leading-6 text-gray-900 hover:text-sky-500 dark:text-white"
                        >
                            <template v-if="lang == 'US'"
                                >Server Mechanics</template
                            >
                            <template v-else-if="lang == 'ES'"
                                >Mecánicas del Servidor</template
                            >
                            <template v-else-if="lang == 'PT'"
                                >Mecânica do Servidor</template
                            >
                            <template v-else-if="lang == 'FR'"
                                >Mécanique du serveur</template
                            >
                            <span aria-hidden="true">→</span></Link
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- /// ABOUT SECTION /// -->

    <section id="news-section" class="min-h-screen relative md:overflow-hidden">
        <video
            autoplay
            muted
            loop
            class="absolute inset-0 w-full h-full object-cover dark:filter dark:brightness-50"
        >
            <source src="img/about.mp4" type="video/mp4" />
            Tu navegador no soporta el elemento de video.
        </video>

        <div class="container mx-auto h-full relative z-10">
            <div class="h-full flex flex-col items-center justify-center">
                <div
                    class="h-full flex flex-col items-center justify-evenly p-2 md:p-5"
                >
                    <h1
                        class="text-4xl lg:text-5xl lg:w-full font-bold tracking-tight text-white drop-shadow w-full text-center"
                    >
                        <template v-if="lang == 'US'">Stay Updated</template>
                        <template v-else-if="lang == 'ES'"
                            >Mantente Actualizado</template
                        >
                        <template v-else-if="lang == 'PT'"
                            >Fique Atualizado</template
                        >
                        <template v-else-if="lang == 'FR'"
                            >Restez à jour</template
                        >
                    </h1>
                    <div class="flex flex-wrap h-full lg:h-[80%] mt-3">
                        <div
                            class="md:p-5 w-full h-[30rem] lg:w-1/2 lg:h-[43rem] md:flex items-center"
                        >
                            <iframe
                                :src="`https://e.widgetbot.io/channels/${$page.props.widgetbot.server}/${$page.props.widgetbot.channel}`"
                                class="h-[%100] shadow-lg rounded-xl w-full h-full lg:h-[70%] block"
                            ></iframe>
                        </div>
                        <div class="p-2 mt-2 md:p-5 lg:w-1/2 lg:my-auto">
                            <div>
                                <h2
                                    class="text-center md:text-left md:text-xl text-2xl uppercase font-bold text-white"
                                >
                                    <template v-if="lang == 'US'"
                                        >About Us</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Sobre Nosotros</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Sobre Nós</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >À propos de nous</template
                                    >
                                </h2>
                                <p
                                    class="text-center md:text-left text-base sm:text-lg md:text-xl mt-2 text-white"
                                >
                                    <span
                                        class="font-bold text-sky-400 text-2xl"
                                        >xPanel</span
                                    >
                                    <template v-if="lang == 'US'">
                                        is a private server of Ragnarok Online,
                                        we are a community that aims to bring
                                        back the good old times! We have many
                                        custom additions that will get you
                                        thrilled and busy! In our community you
                                        will find infinity of surprises that you
                                        will not be able to see in the rest of
                                        the servers and Staff committed in
                                        providing the best support.
                                    </template>
                                    <template v-else-if="lang == 'ES'">
                                        es un servidor privado de Ragnarok
                                        Online, somos una comunidad que tiene
                                        como objetivo traer los buenos viejos
                                        tiempos! Tenemos muchas contenido
                                        personalizado que te emocionarán y te
                                        mantendrán ocupado! En nuestra comunidad
                                        encontrarás infinitas sorpresas que no
                                        podrás ver en el resto de los servidores
                                        y un Staff comprometido en brindar el
                                        mejor soporte.
                                    </template>
                                    <template v-else-if="lang == 'PT'">
                                        é um servidor privado de Ragnarok
                                        Online, somos uma comunidade que tem
                                        como objetivo trazer de volta os bons e
                                        velhos tempos! Temos muitas adições
                                        personalizadas que vão te deixar
                                        emocionado e ocupado! Em nossa
                                        comunidade você encontrará infinitas
                                        surpresas que você não poderá ver no
                                        resto dos servidores e uma equipe
                                        comprometida em fornecer o melhor
                                        suporte.
                                    </template>
                                    <template v-else-if="lang == 'FR'">
                                        est un serveur privé de Ragnarok Online,
                                        nous sommes une communauté qui vise à
                                        ramener les bons vieux temps! Nous avons
                                        de nombreuses ajouts personnalisés qui
                                        vous rendront enthousiaste et occupé!
                                        Dans notre communauté, vous trouverez
                                        une infinité de surprises que vous ne
                                        pourrez pas voir dans le reste des
                                        serveurs et une équipe engagée à fournir
                                        le meilleur support.
                                    </template>
                                </p>
                            </div>
                            <div class="mt-10">
                                <h3
                                    class="text-center md:text-left md:text-xl text-2xl uppercase font-bold text-white"
                                >
                                    <template v-if="lang == 'US'"
                                        >Community</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Comunidad</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Comunidade</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Communauté</template
                                    >
                                </h3>
                                <p
                                    class="text-center md:text-left text-base sm:text-lg md:text-xl mt-2 text-white"
                                >
                                    <template v-if="lang == 'US'">
                                        We see no reason to have dead forums, we
                                        communicate in real time using Discord.
                                        Do you have any questions, suggestions,
                                        or ideas? Feel free to ask us on our
                                        Discord channel.
                                    </template>
                                    <template v-else-if="lang == 'ES'">
                                        No vemos razón para tener foros muertos,
                                        nos comunicamos en tiempo real usando
                                        Discord. ¿Tienes alguna pregunta,
                                        sugerencia o idea? No dudes en
                                        preguntarnos en nuestro canal de
                                        Discord.
                                    </template>
                                    <template v-else-if="lang == 'PT'">
                                        Não vemos motivo para ter fóruns mortos,
                                        comunicamos em tempo real usando o
                                        Discord. Você tem alguma pergunta,
                                        sugestão ou ideia? Sinta-se à vontade
                                        para nos perguntar em nosso canal do
                                        Discord.
                                    </template>
                                    <template v-else-if="lang == 'FR'">
                                        Nous ne voyons aucune raison d'avoir des
                                        forums morts, nous communiquons en temps
                                        réel en utilisant Discord. Vous avez des
                                        questions, des suggestions ou des idées?
                                        N'hésitez pas à nous demander sur notre
                                        chaîne Discord.
                                    </template>
                                </p>
                            </div>
                        </div>

                        <div class="hidden p-5">
                            <div
                                class="w-4/5 uppercase text-2xl font-bold flex justify-start mt-5"
                            >
                                <button
                                    class="mr-5 uppercase rounded-lg text-white h-[60px] bg-gradient-to-r from-sky-400 to-blue-500 py-1 px-4 font-bold text-lg"
                                >
                                    Download
                                </button>
                                <button
                                    class="uppercase rounded-lg text-white h-[60px] bg-gradient-to-r from-sky-400 to-blue-500 py-1 px-4 font-bold text-lg"
                                >
                                    Create Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- /// FEATURES SECTION /// -->

    <div class="relative">
        <video
            autoplay
            muted
            loop
            class="absolute inset-0 w-full h-full object-cover dark:filter dark:brightness-50"
        >
            <source src="img/info.mp4" type="video/mp4" />
            Tu navegador no soporta el elemento de video.
        </video>
        <dh-component>
            <section class="relative z-10 max-w-8xl mx-auto container py-16">
                <div class="mb-5">
                    <div
                        role="contentinfo"
                        class="flex items-center flex-col px-4 text-xl"
                    >
                        <h1
                            class="mt-10 text-4xl font-bold tracking-tight text-white sm:text-5xl drop-shadow"
                        >
                            <template v-if="lang == 'US'"
                                >Server Information</template
                            >
                            <template v-else-if="lang == 'ES'"
                                >Información del Servidor</template
                            >
                            <template v-else-if="lang == 'PT'"
                                >Informações do Servidor</template
                            >
                            <template v-else-if="lang == 'FR'"
                                >Informations sur le serveur</template
                            >
                        </h1>
                    </div>
                    <div
                        tabindex="0"
                        aria-label="group of cards"
                        class="focus:outline-none mt-20 flex flex-wrap justify-center gap-10 px-4"
                    >
                        <div
                            tabindex="0"
                            aria-label="card 1"
                            class="focus:outline-none flex sm:w-full md:w-5/12"
                        >
                            <div class="w-40 h-40 relative mr-5">
                                <img src="img/exp.png" alt="drawer" />
                            </div>
                            <div class="w-10/12">
                                <h2
                                    tabindex="0"
                                    class="focus:outline-none text-xl font-bold leading-tight text-white drop-shadow"
                                >
                                    Rates 100x100x50x
                                </h2>
                                <p
                                    tabindex="0"
                                    class="focus:outline-none text-lg leading-normal pt-2"
                                >
                                    <template v-if="lang == 'US'">
                                        Average rates for you to have fun and
                                        level up your character quickly.
                                    </template>
                                    <template v-else-if="lang == 'ES'"
                                        >Rates medios para que te diviertas y
                                        subas de nivel a tu personaje
                                        rápidamente.
                                    </template>
                                    <template v-else-if="lang == 'PT'"
                                        >Taxas médias para você se divertir e
                                        subir de nível seu personagem
                                        rapidamente.
                                    </template>
                                    <template v-else-if="lang == 'FR'"
                                        >Taux moyens pour vous amuser et monter
                                        de niveau votre personnage rapidement.
                                    </template>
                                </p>
                            </div>
                        </div>
                        <div
                            tabindex="0"
                            aria-label="card 2"
                            class="focus:outline-none flex sm:w-full md:w-5/12"
                        >
                            <div class="w-40 h-40 relative mr-5">
                                <img src="img/epi.png" alt="check" />
                            </div>
                            <div class="w-10/12">
                                <h2
                                    tabindex="0"
                                    class="focus:outline-none text-xl font-bold leading-tight text-white drop-shadow"
                                >
                                    <template v-if="lang == 'US'"
                                        >Max Level: 99/70</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Nivel Máximo: 99/70</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Nível Máximo: 99/70</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Niveau maximum: 99/70</template
                                    >
                                </h2>
                                <p
                                    tabindex="0"
                                    class="focus:outline-none text-lg leading-normal pt-2"
                                >
                                    <template v-if="lang == 'US'"
                                        >Server is Pre-Renewall and you will be
                                        able to level up to level 99/70, with
                                        new skills and exclusive
                                        items.</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >El servidor es Pre-Renewall y podrás
                                        subir hasta el nivel 99/70, con nuevas
                                        habilidades y objetos
                                        exclusivos.</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >O servidor é Pre-Renewall e você poderá
                                        subir até o nível 99/70, com novas
                                        habilidades e itens
                                        exclusivos.</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Le serveur est Pre-Renewall et vous
                                        pourrez monter jusqu'au niveau 99/70,
                                        avec de nouvelles compétences et des
                                        objets exclusifs.</template
                                    >
                                </p>
                            </div>
                        </div>
                        <div
                            tabindex="0"
                            aria-label="card 3"
                            class="focus:outline-none flex sm:w-full md:w-5/12"
                        >
                            <div class="w-40 h-40 relative mr-5">
                                <img src="img/epi.png" alt="html tag" />
                            </div>
                            <div class="w-10/12">
                                <h2
                                    tabindex="0"
                                    class="focus:outline-none text-xl font-bold leading-tight text-white drop-shadow"
                                >
                                    <template v-if="lang == 'US'"
                                        >Episode: 13.3+ - El Dicastes</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Episodio: 13.3+ - El Dicastes</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Episódio: 13.3+ - El Dicastes</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Épisode: 13.3+ - El Dicastes</template
                                    >
                                </h2>
                                <p
                                    tabindex="0"
                                    class="focus:outline-none text-lg leading-normal pt-2"
                                >
                                    <template v-if="lang == 'US'"
                                        >You can now explore Nidhoggr's episode,
                                        with new instances and exclusive
                                        items.</template
                                    >

                                    <template v-else-if="lang == 'ES'"
                                        >Ahora puedes explorar el episodio de
                                        Nidhoggr's, con nuevas instancias y
                                        objetos exclusivos.</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Agora você pode explorar o episódio de
                                        Nidhoggr's, com novas instâncias e itens
                                        exclusivos.</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Vous pouvez maintenant explorer
                                        l'épisode de Nidhoggr's, avec de
                                        nouvelles instances et des objets
                                        exclusifs.</template
                                    >
                                </p>
                            </div>
                        </div>
                        <div
                            tabindex="0"
                            aria-label="card 4"
                            class="focus:outline-none flex sm:w-full md:w-5/12"
                        >
                            <div class="w-40 h-40 relative mr-5">
                                <img src="img/inter.png" alt="monitor" />
                            </div>
                            <div class="w-10/12">
                                <h2
                                    tabindex="0"
                                    class="focus:outline-none text-xl font-bold leading-tight text-white drop-shadow"
                                >
                                    <template v-if="lang == 'US'"
                                        >International Server</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Servidor Internacional</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Servidor Internacional</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Serveur international</template
                                    >
                                </h2>
                                <p
                                    tabindex="0"
                                    class="focus:outline-none text-lg leading-normal pt-2"
                                >
                                    <template v-if="lang == 'US'"
                                        >We have the possibility of translating
                                        our NPC's in different languages so that
                                        the language barrier is not a
                                        problem.</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Tenemos la posibilidad de traducir
                                        nuestros NPC's en diferentes idiomas
                                        para que la barrera del idioma no sea un
                                        problema.</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Temos a possibilidade de traduzir
                                        nossos NPC's em diferentes idiomas para
                                        que a barreira do idioma não seja um
                                        problema.</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Nous avons la possibilité de traduire
                                        nos NPC dans différentes langues afin
                                        que la barrière de la langue ne soit pas
                                        un problème.</template
                                    >
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="antialiased text-gray-600 mb-5">
                    <div class="flex flex-wrap justify-center gap-y-5">
                        <!-- Card -->
                        <div class="max-w-xs lg:w-1/5 md:mr-5">
                            <div
                                class="flex flex-col h-full bg-white shadow-lg rounded-lg overflow-hidden"
                            >
                                <!-- Image -->
                                <a
                                    class="block focus:outline-none focus-visible:ring-2"
                                    href="/wiki/systems/battleground"
                                >
                                    <figure
                                        class="relative h-0 pb-[56.25%] overflow-hidden"
                                    >
                                        <img
                                            class="absolute inset-0 w-full h-full object-cover transform hover:scale-105 transition duration-700 ease-out"
                                            src="img/bg.gif"
                                            width="320"
                                            height="180"
                                            alt="Course"
                                        />
                                    </figure>
                                </a>
                                <!-- Card Content -->
                                <div
                                    class="flex-grow flex flex-col p-5 dark:bg-gray-800"
                                >
                                    <!-- Card body -->
                                    <div class="flex-grow text-center">
                                        <!-- Header -->
                                        <header class="mb-3">
                                            <a
                                                class="block focus:outline-none focus-visible:ring-2"
                                                href="/wiki/systems/battleground"
                                            >
                                                <h3
                                                    class="text-[22px] text-gray-900 font-bold leading-snug dark:text-white"
                                                >
                                                    <template
                                                        v-if="lang == 'US'"
                                                        >New
                                                        Battlegrounds</template
                                                    >
                                                    <template
                                                        v-else-if="lang == 'ES'"
                                                        >Nuevos
                                                        Battlegrounds</template
                                                    >
                                                    <template
                                                        v-else-if="lang == 'PT'"
                                                        >Novas Batalhas
                                                        Campais</template
                                                    >
                                                    <template
                                                        v-else-if="lang == 'FR'"
                                                        >Nouveaux champs de
                                                        bataille</template
                                                    >
                                                </h3>
                                            </a>
                                        </header>
                                        <!-- Content -->
                                        <div class="mb-8 dark:text-white">
                                            <p>
                                                <template v-if="lang == 'US'"
                                                    >You will discover new
                                                    Battleground modes that you
                                                    won't find on other servers,
                                                    designed to impxPanele GvG and
                                                    gameplay!</template
                                                >
                                                <template
                                                    v-else-if="lang == 'ES'"
                                                    >Descubrirás nuevos modos de
                                                    Battlegrounds que no
                                                    encontrarás en otros
                                                    servidores, diseñados para
                                                    mejorar el GvG y la
                                                    jugabilidad!</template
                                                >
                                                <template
                                                    v-else-if="lang == 'PT'"
                                                    >Você descobrirá novos modos
                                                    de Batalha Campal que não
                                                    encontrará em outros
                                                    servidores, projetados para
                                                    melhorar o GvG e a
                                                    jogabilidade!</template
                                                >
                                                <template
                                                    v-else-if="lang == 'FR'"
                                                    >Vous découvrirez de
                                                    nouveaux modes de champ de
                                                    bataille que vous ne
                                                    trouverez pas sur d'autres
                                                    serveurs, conçus pour
                                                    améliorer le GvG et le
                                                    gameplay!</template
                                                >
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Card footer -->
                                    <div class="flex justify-center space-x-2">
                                        <a
                                            class="font-semibold text-sm inline-flex items-center justify-center px-3 py-1.5 border border-transparent rounded leading-5 shadow-sm transition duration-150 ease-in-out bg-sky-500 focus:outline-none focus-visible:ring-2 hover:bg-sky-600 text-white"
                                            href="/wiki/systems/battleground"
                                        >
                                            <template v-if="lang == 'US'"
                                                >More Info</template
                                            >
                                            <template v-else-if="lang == 'ES'"
                                                >Más Información</template
                                            >
                                            <template v-else-if="lang == 'PT'"
                                                >Mais Informações</template
                                            >
                                            <template v-else-if="lang == 'FR'"
                                                >Plus d'informations</template
                                            >
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card -->
                        <div class="max-w-xs lg:w-1/5 md:mr-5">
                            <div
                                class="flex flex-col h-full bg-white shadow-lg rounded-lg overflow-hidden"
                            >
                                <!-- Image -->
                                <a
                                    class="block focus:outline-none focus-visible:ring-2"
                                    href="/wiki/systems/battlepass"
                                >
                                    <figure
                                        class="relative h-0 pb-[56.25%] overflow-hidden"
                                    >
                                        <img
                                            class="absolute inset-0 w-full h-full object-cover transform hover:scale-105 transition duration-700 ease-out"
                                            src="img/battlepass.gif"
                                            width="320"
                                            height="180"
                                            alt="Course"
                                        />
                                    </figure>
                                </a>
                                <!-- Card Content -->
                                <div
                                    class="flex-grow flex flex-col p-5 dark:bg-gray-800"
                                >
                                    <!-- Card body -->
                                    <div class="flex-grow text-center">
                                        <!-- Header -->
                                        <header class="mb-3">
                                            <a
                                                class="block focus:outline-none focus-visible:ring-2"
                                                href="/wiki/systems/battlepass"
                                            >
                                                <h3
                                                    class="text-[22px] text-gray-900 font-bold leading-snug dark:text-white"
                                                >
                                                    <template
                                                        v-if="lang == 'US'"
                                                        >Battle Pass</template
                                                    >
                                                    <template
                                                        v-else-if="lang == 'ES'"
                                                        >Pase de
                                                        Batalla</template
                                                    >
                                                    <template
                                                        v-else-if="lang == 'PT'"
                                                        >Passe de
                                                        Batalha</template
                                                    >
                                                    <template
                                                        v-else-if="lang == 'FR'"
                                                        >Passe de
                                                        bataille</template
                                                    >
                                                </h3>
                                            </a>
                                        </header>
                                        <!-- Content -->
                                        <div class="mb-8 dark:text-white">
                                            <p>
                                                <template v-if="lang == 'US'">
                                                    The Battle Pass is a new
                                                    monthly system that rewards
                                                    players with exclusive items
                                                    based on level progression.
                                                </template>
                                                <template
                                                    v-else-if="lang == 'ES'"
                                                >
                                                    El Pase de Batalla es un
                                                    nuevo sistema mensual que
                                                    recompensa a los jugadores
                                                    con objetos exclusivos
                                                    basados en la progresión de
                                                    nivel.
                                                </template>
                                                <template
                                                    v-else-if="lang == 'PT'"
                                                >
                                                    O Passe de Batalha é um novo
                                                    sistema mensal que
                                                    recompensa os jogadores com
                                                    itens exclusivos com base na
                                                    progressão de nível.
                                                </template>
                                                <template
                                                    v-else-if="lang == 'FR'"
                                                >
                                                    Le passe de bataille est un
                                                    nouveau système mensuel qui
                                                    récompense les joueurs avec
                                                    des objets exclusifs basés
                                                    sur la progression du
                                                    niveau.
                                                </template>
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Card footer -->
                                    <div class="flex justify-center space-x-2">
                                        <a
                                            class="font-semibold text-sm inline-flex items-center justify-center px-3 py-1.5 border border-transparent rounded leading-5 shadow-sm transition duration-150 ease-in-out bg-sky-500 focus:outline-none focus-visible:ring-2 hover:bg-sky-600 text-white"
                                            href="/wiki/systems/battlepass"
                                        >
                                            <template v-if="lang == 'US'"
                                                >More Info</template
                                            >
                                            <template v-else-if="lang == 'ES'"
                                                >Más Información</template
                                            >
                                            <template v-else-if="lang == 'PT'"
                                                >Mais Informações</template
                                            >
                                            <template v-else-if="lang == 'FR'"
                                                >Plus d'informations</template
                                            ></a
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card -->
                        <div class="max-w-xs lg:w-1/5 md:mr-5">
                            <div
                                class="flex flex-col h-full bg-white shadow-lg rounded-lg overflow-hidden"
                            >
                                <!-- Image -->
                                <a
                                    class="block focus:outline-none focus-visible:ring-2"
                                    href="/wiki/systems/championmobs"
                                >
                                    <figure
                                        class="relative h-0 pb-[56.25%] overflow-hidden"
                                    >
                                        <img
                                            class="absolute inset-0 w-full h-full object-cover transform hover:scale-105 transition duration-700 ease-out"
                                            src="img/championmob.gif"
                                            width="320"
                                            height="180"
                                            alt="Course"
                                        />
                                    </figure>
                                </a>
                                <!-- Card Content -->
                                <div
                                    class="flex-grow flex flex-col p-5 dark:bg-gray-800"
                                >
                                    <!-- Card body -->
                                    <div class="flex-grow text-center">
                                        <!-- Header -->
                                        <header class="mb-3">
                                            <a
                                                class="block focus:outline-none focus-visible:ring-2"
                                                href="/wiki/systems/championmobs"
                                            >
                                                <h3
                                                    class="text-[22px] text-gray-900 font-bold leading-snug dark:text-white"
                                                >
                                                    <template
                                                        v-if="lang == 'US'"
                                                        >Champion Mobs</template
                                                    >
                                                    <template
                                                        v-else-if="lang == 'ES'"
                                                        >Monstruos
                                                        Campeones</template
                                                    >
                                                    <template
                                                        v-else-if="lang == 'PT'"
                                                        >Monstros
                                                        Campeões</template
                                                    >
                                                    <template
                                                        v-else-if="lang == 'FR'"
                                                        >Monstres
                                                        Champions</template
                                                    >
                                                </h3>
                                            </a>
                                        </header>
                                        <!-- Content -->
                                        <div class="mb-8 dark:text-white">
                                            <p>
                                                <template v-if="lang == 'US'"
                                                    >Now the adventurers will
                                                    face an even greater threat:
                                                    the Champion Monsters. Check
                                                    out the exclusive drop
                                                    information you can
                                                    get!</template
                                                >
                                                <template
                                                    v-else-if="lang == 'ES'"
                                                >
                                                    Ahora los aventureros se
                                                    enfrentarán a una amenaza
                                                    aún mayor: los Monstruos
                                                    Campeones. ¡Consulte la
                                                    información de los drops
                                                    exclusivos que puedes
                                                    obtener!</template
                                                >
                                                <template
                                                    v-else-if="lang == 'PT'"
                                                >
                                                    Agora os aventureiros
                                                    enfrentarão uma ameaça ainda
                                                    maior: os Monstros Campeões.
                                                    Confira as informações
                                                    exclusivas de queda que você
                                                    pode obter!</template
                                                >
                                                <template
                                                    v-else-if="lang == 'FR'"
                                                >
                                                    Maintenant, les aventuriers
                                                    feront face à une menace
                                                    encore plus grande: les
                                                    monstres champions.
                                                    Découvrez les informations
                                                    de chute exclusives que vous
                                                    pouvez obtenir!</template
                                                >
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Card footer -->
                                    <div class="flex justify-center space-x-2">
                                        <a
                                            class="font-semibold text-sm inline-flex items-center justify-center px-3 py-1.5 border border-transparent rounded leading-5 shadow-sm transition duration-150 ease-in-out bg-sky-500 focus:outline-none focus-visible:ring-2 hover:bg-sky-600 text-white"
                                            href="/wiki/systems/championmobs"
                                        >
                                            <template v-if="lang == 'US'"
                                                >More Info</template
                                            >
                                            <template v-else-if="lang == 'ES'"
                                                >Más Información</template
                                            >
                                            <template v-else-if="lang == 'PT'"
                                                >Mais Informações</template
                                            >
                                            <template v-else-if="lang == 'FR'"
                                                >Plus d'informations</template
                                            ></a
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card -->
                        <div class="max-w-xs lg:w-1/5 md:mr-5">
                            <div
                                class="flex flex-col h-full bg-white shadow-lg rounded-lg overflow-hidden"
                            >
                                <!-- Image -->
                                <a
                                    class="block focus:outline-none focus-visible:ring-2"
                                    href="/wiki/systems/petevolution"
                                >
                                    <figure
                                        class="relative h-0 pb-[56.25%] overflow-hidden"
                                    >
                                        <img
                                            class="absolute inset-0 w-full h-full object-cover transform hover:scale-105 transition duration-700 ease-out"
                                            src="img/pet.gif"
                                            width="320"
                                            height="180"
                                            alt="Course"
                                        />
                                    </figure>
                                </a>
                                <!-- Card Content -->
                                <div
                                    class="flex-grow flex flex-col p-5 dark:bg-gray-800"
                                >
                                    <!-- Card body -->
                                    <div class="flex-grow text-center">
                                        <!-- Header -->
                                        <header class="mb-3">
                                            <a
                                                class="block focus:outline-none focus-visible:ring-2"
                                                href="/wiki/systems/petevolution"
                                            >
                                                <h3
                                                    class="text-[22px] text-gray-900 font-bold leading-snug dark:text-white"
                                                >
                                                    <template
                                                        v-if="lang == 'US'"
                                                        >Pet Evolution</template
                                                    >
                                                    <template
                                                        v-else-if="lang == 'ES'"
                                                        >Evolución de
                                                        Pets</template
                                                    >
                                                    <template
                                                        v-else-if="lang == 'PT'"
                                                        >Evolução de
                                                        Mascotes</template
                                                    >
                                                    <template
                                                        v-else-if="lang == 'FR'"
                                                        >Évolution des animaux
                                                        de compagnie</template
                                                    >
                                                </h3>
                                            </a>
                                        </header>
                                        <!-- Content -->
                                        <div class="mb-8 dark:text-white">
                                            <p>
                                                <template v-if="lang == 'US'"
                                                    >The function of evolving
                                                    the pet is to improve xPanel its
                                                    status. Besides that, it
                                                    also gives you a change of
                                                    appearance in most of the
                                                    pets.</template
                                                >
                                                <template
                                                    v-else-if="lang == 'ES'"
                                                >
                                                    La función de evolucionar tu
                                                    pet permite mejorar su
                                                    estado. Además de eso,
                                                    también te da un cambio de
                                                    apariencia en la mayoría de
                                                    los pets.</template
                                                >
                                                <template
                                                    v-else-if="lang == 'PT'"
                                                >
                                                    A função de evoluir o animal
                                                    de estimação é melhorar seu
                                                    status. Além disso, também
                                                    dá a você uma mudança de
                                                    aparência na maioria dos
                                                    animais de
                                                    estimação.</template
                                                >
                                                <template
                                                    v-else-if="lang == 'FR'"
                                                >
                                                    La fonction d'évolution de
                                                    l'animal de compagnie est
                                                    d'améliorer son statut. En
                                                    plus de cela, il vous donne
                                                    également un changement
                                                    d'apparence dans la plupart
                                                    des animaux de
                                                    compagnie.</template
                                                >
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Card footer -->
                                    <div class="flex justify-center space-x-2">
                                        <a
                                            class="font-semibold text-sm inline-flex items-center justify-center px-3 py-1.5 border border-transparent rounded leading-5 shadow-sm transition duration-150 ease-in-out bg-sky-500 focus:outline-none focus-visible:ring-2 hover:bg-sky-600 text-white object-center"
                                            href="/wiki/systems/petevolution"
                                            >More Info</a
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </dh-component>
    </div>
</template>
