<script setup>
import { onMounted, ref } from "vue";
const props = defineProps(["guild", "all_guilds", "vs_data", "index"]);
</script>

<template>
    <tr class="spaceUnder">
        <td colspan="9">
            <table class="w-full">
                <thead class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md">
                    <th class="p-2 w-1/5">Guild</th>
                    <th class="p-2">vs</th>
                    <th class="p-2 w-1/5">Guild</th>
                    <th class="p-2 w-1/5">Total Kills</th>
                    <th class="p-2 w-1/5">Total Deaths</th>
                    <th class="p-2 w-1/5">Total K/D</th>
                </thead>
                <tbody>
                    <tr v-for="(vs_guilds, index) in all_guilds">
                        <template
                            v-if="vs_guilds.guild_id != props.guild.guild_id"
                        >
                            <td class="px-5 py-3">
                                <div class="flex justify-center items-center">
                                    <div
                                        class="flex items-center justify-center"
                                        :class="{
                                            ['text-[#04aaff]']:
                                                props.vs_data[
                                                    props.guild.guild_id
                                                ][vs_guilds.guild_id][0].kills >
                                                props.vs_data[
                                                    props.guild.guild_id
                                                ][vs_guilds.guild_id][0].deaths,
                                        }"
                                    >
                                        <img
                                            class="mr-2"
                                            :src="`/storage/cache/tmp_emblem/${props.guild.guild_id}.png`"
                                            alt=""
                                        />
                                        {{ props.guild.gname }}
                                    </div>
                                </div>
                            </td>
                            <td>vs</td>
                            <td class="px-5 py-3">
                                <div class="flex justify-center items-center">
                                    <div
                                        class="flex items-center justify-center"
                                        :class="{
                                            'text-[#04aaff]':
                                                props.vs_data[
                                                    props.guild.guild_id
                                                ][vs_guilds.guild_id][0].kills <
                                                props.vs_data[
                                                    props.guild.guild_id
                                                ][vs_guilds.guild_id][0].deaths,
                                        }"
                                    >
                                        <!-- <img
                                        class="mr-2"
                                        :src="`/${url[vs_guilds.guild_id]} `"
                                        alt=""
                                        /> -->
                                        <img
                                            class="mr-2"
                                            :src="`/storage/cache/tmp_emblem/${vs_guilds.guild_id}.png`"
                                            alt=""
                                        />
                                        {{ vs_guilds.name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex justify-center items-center">
                                    <div
                                        class="flex items-center justify-center"
                                    >
                                        {{
                                            props.vs_data != null
                                                ? props.vs_data[
                                                      props.guild.guild_id
                                                  ][vs_guilds.guild_id][0].kills
                                                : 0
                                        }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex justify-center items-center">
                                    <div
                                        class="flex items-center justify-center"
                                    >
                                        {{
                                            props.vs_data != null
                                                ? props.vs_data[
                                                      props.guild.guild_id
                                                  ][vs_guilds.guild_id][0]
                                                      .deaths
                                                : 0
                                        }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex justify-center items-center">
                                    <div
                                        class="flex items-center justify-center"
                                    >
                                        {{
                                            props.vs_data != null
                                                ? props.vs_data[
                                                      props.guild.guild_id
                                                  ][vs_guilds.guild_id][0]
                                                      .kills -
                                                  props.vs_data[
                                                      props.guild.guild_id
                                                  ][vs_guilds.guild_id][0]
                                                      .deaths
                                                : 0
                                        }}
                                    </div>
                                </div>
                            </td>
                        </template>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</template>
