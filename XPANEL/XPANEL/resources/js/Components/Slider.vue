<template>
    <swiper
        :modules="modules"
        :slides-per-view="1"
        :space-between="0"
        navigation
        
        @swiper="onSwiper"
        @slideChange="onSlideChange"
    >
        <swiper-slide><img src="/img/jackpot.png" alt="" /></swiper-slide>
        <swiper-slide><img src="/img/battlegrounds.png" alt="" /></swiper-slide>
        <swiper-slide
            ><img src="/img/hunting-mission.png" alt=""
        /></swiper-slide>
        <swiper-slide><img src="/img/costumes.png" alt="" /></swiper-slide>
    </swiper>
</template>
<style scoped>
.swiper-slide{
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
} 
</style>
<script>
// import Swiper core and required modules
import { Navigation, Pagination, Scrollbar, A11y } from "swiper";

// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";

// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

// Import Swiper styles
export default {
    components: {
        Swiper,
        SwiperSlide,
    },
    setup() {
        const onSwiper = (swiper) => {
            console.log(swiper);
        };
        const onSlideChange = () => {
            console.log("slide change");
        };
        return {
            onSwiper,
            onSlideChange,
            modules: [Navigation, Pagination, Scrollbar, A11y],
        };
    },
};
</script>

<!-- <template>
    <swiper
      :slides-per-view="3"
      :space-between="50"
      @swiper="onSwiper"
      @slideChange="onSlideChange"
    >
      <swiper-slide><img src="/img/jackpot.png" alt=""></swiper-slide>
      <swiper-slide><img src="/img/battlegrounds.png" alt=""></swiper-slide>
      <swiper-slide><img src="/img/hunting-missions.png" alt=""></swiper-slide>
      <swiper-slide><img src="/img/costumes.png" alt=""></swiper-slide>
    </swiper>
  </template>
  <script>
    // Import Swiper Vue.js components
    import { Swiper, SwiperSlide } from 'swiper/vue';

    // Import Swiper styles
    import 'swiper/css';

    export default {
      components: {
        Swiper,
        SwiperSlide,
      },
      setup() {
        const onSwiper = (swiper) => {
          console.log(swiper);
        };
        const onSlideChange = () => {
          console.log('slide change');
        };
        return {
          onSwiper,
          onSlideChange,
        };
      },
    };
  </script>
   -->
