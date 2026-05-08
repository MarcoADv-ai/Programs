<script setup>
import { ref } from "vue";

const sex_img = ref("");
const sex = ref("male");

const basic_classes = ref([
    {
        name: "Swordman",
        sub_clases: [
            {
                name: "swordman",
                active: true,
                desc: "Swordsmen are the embodiment of courage. They specialize in melee combat and act as vanguards in their groups",
            },
            {
                name: "knight",
                active: false,
                desc: "Knights are skilled in the use of swords and lances. In addition, they have incredible mobility on the field thanks to their Peco Peco rides.",
            },
            {
                name: "lord knight",
                active: false,
                desc: "Lord Knights are the ultimate form of Knights. They have formidable attack strength and can crush an enemy's defense in a flash",
            },
        ],
        active: true,
    },
    {
        name: "Archer",
        sub_clases: [
            {
                name: "archer",
                active: true,
                desc: "Archers are specialized in ranged physical attacks. Their flexibility and mobility make them great initiators on the battlefield.",
            },
            {
                name: "hunter",
                active: false,
                desc: "Hunters are experts in bows. With the help of their traps and falcon companions, they are capable of taking down any prey.",
            },
            {
                name: "sniper",
                active: false,
                desc: "One shot, one kill! There's no better way to describe Snipers.",
            },
        ],
        active: false,
    },
    {
        name: "Merchant",
        sub_clases: [
            {
                name: "merchant",
                active: true,
                desc: "Merchants are wealthy... and when one's pocket is full of coins, possibilities could be much more spectacular",
            },
            {
                name: "blacksmith",
                active: false,
                desc: "Blacksmiths are skilled in weapon refining and forging. When it comes to the knowledge of equipment, they have no equal.",
            },
            {
                name: "white smith",
                active: false,
                desc: "Mastersmiths can infuse life into any material existing in the world with their marvelous feat.",
            },
        ],
        active: false,
    },
    {
        name: "Acolyte",
        sub_clases: [
            {
                name: "acolyte",
                active: true,
                desc: "Acolytes are devout followers of the light. Healing and supporting are their forte. They are essential to every group",
            },
            {
                name: "priest",
                active: false,
                desc: "Priests bring miracles with the grace of the light. They are party-play oriented and specialize in healing and cleansing undead.",
            },
            {
                name: "high priest",
                active: false,
                desc: "High Priests are the saviors of life. They can reduce the damage taken of all allies, and when the situation is dire, they can create a sanctuary to fully negate the incoming damage.",
            },
        ],
        active: false,
    },
    {
        name: "Thief",
        sub_clases: [
            {
                name: "thief",
                active: true,
                desc: "Thieves are masters of cloak and dagger. Surprise attacks and ambushes can always turn the tide of battle.",
            },
            {
                name: "assa",
                active: false,
                desc: "Assassins are to be feared with their cunning arts, sneaky ways, and toxic blades..",
            },
            {
                name: "assasin cross",
                active: false,
                desc: "They are the phantom of the night and the poet of the shadow. Wherever they walk, death follows.",
            },
        ],
        active: false,
    },
    {
        name: "Mage",
        sub_clases: [
            {
                name: "mage",
                active: true,
                desc: "Mages possess the power of elements and are specialized in ranged magic attacks.",
            },
            {
                name: "wizard",
                active: false,
                desc: "Wizards have devoted themselves to the study of powerful magic. They are efficient at eliminating groups of enemies with their AoE spells.",
            },
            {
                name: "high wizard",
                active: false,
                desc: "High Wizards are the ultimate form of Wizards. They are capable of casting powerful spells that can turn the tide of battle.",
            },
        ],
        active: false,
    },
]);

function change_class(class_name) {
    basic_classes.value.forEach((basic_class) => {
        if (basic_class.name == class_name) {
            basic_class.active = true;
        } else {
            basic_class.active = false;
        }
    });
}

function change_sex() {
    if (sex.value == "fem") {
        sex.value = "male";
        sex_img.value = "";
    } else {
        sex.value = "fem";
        sex_img.value = "fem";
    }
}

const get_sub_classes = () => {
    let sub_classes = [];
    basic_classes.value.forEach((basic_class) => {
        if (basic_class.active) {
            basic_class.sub_clases.forEach((sub_class) => {
                sub_classes.push(sub_class.name);
            });
        }
    });
    return sub_classes;
};

const get_active_sub_class = () => {
    let active_sub_class = "";
    basic_classes.value.forEach((basic_class) => {
        if (basic_class.active) {
            basic_class.sub_clases.forEach((sub_class) => {
                if (sub_class.active) {
                    active_sub_class = sub_class.name;
                }
            });
        }
    });
    return active_sub_class;
};

const get_desc = () => {
    let desc = "";
    basic_classes.value.forEach((basic_class) => {
        if (basic_class.active) {
            basic_class.sub_clases.forEach((sub_class) => {
                if (sub_class.active) {
                    desc = sub_class.desc;
                }
            });
        }
    });
    return desc;
};

const change_subclass = (sub_class_name) => {
    basic_classes.value.forEach((basic_class) => {
        if (basic_class.active) {
            basic_class.sub_clases.forEach((sub_class) => {
                if (sub_class.name == sub_class_name) {
                    sub_class.active = true;
                } else {
                    sub_class.active = false;
                }
            });
        }
    });
};

const set_previous_class = () => {
    let previous_class = "";
    basic_classes.value.forEach((basic_class, index) => {
        if (basic_class.active) {
            if (index == 0) {
                previous_class =
                    basic_classes.value[basic_classes.value.length - 1].name;
            } else {
                previous_class = basic_classes.value[index - 1].name;
            }
        }
    });
    change_class(previous_class);
};

const set_next_class = () => {
    let next_class = "";
    basic_classes.value.forEach((basic_class, index) => {
        if (basic_class.active) {
            if (index == basic_classes.value.length - 1) {
                next_class = basic_classes.value[0].name;
            } else {
                next_class = basic_classes.value[index + 1].name;
            }
        }
    });
    change_class(next_class);
};

const url_img = (img) => {
    switch (sex_img.value) {
        case "fem":
            return "/img/classes/" + img + " " + sex_img.value + ".png";
        default:
            return "/img/classes/" + img + ".png";
    }
};
</script>

<template>
    <section id="job_classes" class="h-screen">
        <div class="container mx-auto h-full flex flex-col justify-end relative">
            <div class="p-5 text-center text-gray-900 flex items-center justify-center">
                <div class="mr-3 rotate-180">
                    <img src="/img/title.png" alt="" />
                </div>
                <div>
                    <h1 class="text-3xl md:text-5xl font-bold font-serif text-[#0b6dab]">
                        Classes
                    </h1>
                </div>
                <div class="ml-3">
                    <img src="/img/title.png" alt="" />
                </div>
            </div>
            <div class="flex h-full z-10 justify-between flex-row">
                <div class="ml-[10%] flex flex-row items-center justify-center">

                    <div class="w-full flex  flex-wrap justify-center lg:justify-between relative mb-[15rem] md:mb-[20rem]">
                        <div class="flex flex-col md:w-1/2 md:mr-5 lg:w-1/4 lg:mr-0">
                            <div class="">
                                <h2
                                    class="text-3xl md:text-5xl capitalize text-center font-bold text-[#315e80] font-serif mb-5">
                                    {{ get_active_sub_class() }}
                                </h2>
                            </div>
                            <div class="switches-container mb-5">
                                <input type="radio" id="switchMonthly" name="switchPlan" value="Monthly" checked="checked"
                                    @change="change_sex()" />
                                <input type="radio" id="switchYearly" name="switchPlan" value="Yearly"
                                    @change="change_sex()" />
                                <label>Male</label>
                                <label>Female</label>
                                <div class="switch-wrapper">
                                    <div class="switch">
                                        <div class="font-serif text-[1.5rem]">Male</div>
                                        <div class="font-serif text-[1.5rem]">
                                            Female
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" flex justify-around w-full">
                                <div v-for="sub_class in get_sub_classes()">
                                    <img v-if="get_active_sub_class() == sub_class"
                                        :src="`/img/classes/icons/${sex}/${sub_class} icon2.png`" alt="" />
                                    <img v-else @click="change_subclass(sub_class)" class="cursor-pointer"
                                        :src="`/img/classes/icons/${sex}/${sub_class} icon.png`" alt="" />
                                </div>
                            </div>
                        </div>


                        <div class="flex flex-col  justify-center pt-5 md:w-1/2 lg:w-1/3">
                            <p class="lg: text-md md:text-xl lg:text-2xl text-[#70757f] font-serif font-semibold">
                                {{ get_desc() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="absolute w-11/12 bottom-[9.7rem]">
                <img :src="url_img(get_active_sub_class())" alt="" class="cursor-pointer" />
            </div>
            <div class="divider mx-auto"></div>
            <div class="mx-auto p-5 mb-10">
                <ul class="flex flex-row items-center w-full overflow-x-auto">
                    <li class="hidden lg:block mr-7">
                        <img @click="set_previous_class()" src="/img/classes/arrow left.png" alt=""
                            class="cursor-pointer" />
                    </li>
                    <li class="mr-7" v-for="basic_class in basic_classes">
                        <img class="cursor-pointer" v-if="!basic_class.active"
                            :src="`/img/classes/${basic_class.name} class.png`" alt=""
                            @click="change_class(basic_class.name)" />
                        <img v-else :src="`/img/classes/${basic_class.name} class_active.png`" alt="" />
                    </li>

                    <li class="mr-7 hidden lg:block">
                        <img @click="set_next_class()" src="/img/classes/arrow right.png" alt="" class="cursor-pointer" />
                    </li>
                </ul>
            </div>
        </div>
    </section>
</template>

<style scoped>


.job-model {
    background-image: url("/img/classes/job_model.png");
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    height: 100%;
}

.divider {
    height: 4px;
    width: 66%;
    background: #419ad2;
}

.switches-container {
    width: 16rem;
    position: relative;
    display: flex;
    padding: 0;
    position: relative;
    background: #8ec0d9;
    line-height: 3rem;
    border-radius: 3rem;
    margin-left: auto;
    margin-right: auto;
}

/* input (radio) for toggling. hidden - use labels for clicking on */
.switches-container input {
    visibility: hidden;
    position: absolute;
    top: 0;
}

/* labels for the input (radio) boxes - something to click on */
.switches-container label {
    width: 50%;
    padding: 0;
    margin: 0;
    text-align: center;
    cursor: pointer;
    font: bold 1.5rem/3rem "Sans serif";
    /* font-family: "Sans serif" */
    color: #d9eaf1;
}

/* switch highlighters wrapper (sliding left / right) 
    - need wrapper to enable the even margins around the highlight box
*/
.switch-wrapper {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 50%;
    padding: 0.15rem;
    z-index: 3;
    transition: transform 0.5s cubic-bezier(0.77, 0, 0.175, 1);
    /* transition: transform 1s; */
}

/* switch box highlighter */
.switch {
    border-radius: 3rem;
    background: #d9eaf1;
    color: #0d6ed5;
    font: bold 1.5rem/3rem "Helvetica Neue", Helvetica, Arial, sans-serif;
    height: 100%;
}

/* switch box labels
    - default setup
    - toggle afterwards based on radio:checked status 
*/
.switch div {
    width: 100%;
    text-align: center;
    opacity: 0;
    display: block;
    color: var(--switch-text-color);
    transition: opacity 0.2s cubic-bezier(0.77, 0, 0.175, 1) 0.125s;
    will-change: opacity;
    position: absolute;
    top: 0;
    left: 0;
}

/* slide the switch box from right to left */
.switches-container input:nth-of-type(1):checked~.switch-wrapper {
    transform: translateX(0%);
}

/* slide the switch box from left to right */
.switches-container input:nth-of-type(2):checked~.switch-wrapper {
    transform: translateX(100%);
}

/* toggle the switch box labels - first checkbox:checked - show first switch div */
.switches-container input:nth-of-type(1):checked~.switch-wrapper .switch div:nth-of-type(1) {
    opacity: 1;
}

/* toggle the switch box labels - second checkbox:checked - show second switch div */
.switches-container input:nth-of-type(2):checked~.switch-wrapper .switch div:nth-of-type(2) {
    opacity: 1;
}
</style>
