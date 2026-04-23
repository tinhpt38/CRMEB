<template>
  <view class="time" :style="justifyLeft">
    <text class="red" v-if="tipText">{{ tipText }}</text>
    <text class="styleAll" :style="[timeStyle]" v-if="isDay === true">{{
      day
    }}</text>
    <text class="timeTxt red" :style="[timeTxtStyle]" v-if="dayText">{{
      dayText
    }}</text>
    <text class="styleAll" :style="[timeStyle]">{{ hour }}</text>
    <text class="timeTxt red" :style="[timeTxtStyle]" v-if="hourText">{{
      hourText
    }}</text>
    <text class="styleAll" :style="[timeStyle]">{{ minute }}</text>
    <text class="timeTxt red" :style="[timeTxtStyle]" v-if="minuteText">{{
      minuteText
    }}</text>
    <text class="styleAll" :style="[timeStyle]">{{ second }}</text>
    <text class="timeTxt red" :style="[timeTxtStyle]" v-if="secondText">{{
      secondText
    }}</text>
  </view>
</template>

<script>
export default {
  name: "countDown",
  props: {
    justifyLeft: {
      type: String,
      default: "",
    },
    //Văn bản nhắc nhở xuất phát khoảng cách
    tipText: {
      type: String,
      default: "Đếm ngược",
    },
    dayText: {
      type: String,
      default: "bầu trời",
    },
    hourText: {
      type: String,
      default: "giờ",
    },
    minuteText: {
      type: String,
      default: "điểm",
    },
    secondText: {
      type: String,
      default: "Thứ hai",
    },
    datatime: {
      type: Number,
      default: 0,
    },
    isDay: {
      type: Boolean,
      default: true,
    },
    bgColor: {
      type: String,
      default: "",
    },
    colors: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      day: "00",
      hour: "00",
      minute: "00",
      second: "00",
    };
  },
  computed: {
    timeStyle() {
      return {
        background: this.bgColor,
        color: this.colors,
      };
    },
    timeTxtStyle() {
      if (this.colors === "rgba(255, 255, 255, 0)") {
        return {
          color: "transparent",
        };
      }
      return {};
    },
  },
  created() {},
  mounted() {
    this.show_time();
  },
  methods: {
    show_time() {
      let that = this;

      function runTime() {
        //chức năng thời gian
        let intDiff = that.datatime - Date.parse(new Date()) / 1000; //Lấy chênh lệch thời gian của dấu thời gian trong dữ liệu；
        let day = 0,
          hour = 0,
          minute = 0,
          second = 0;
        if (intDiff > 0) {
          //thời gian chuyển đổi
          if (that.isDay === true) {
            day = Math.floor(intDiff / (60 * 60 * 24));
          } else {
            day = 0;
          }
          hour = Math.floor(intDiff / (60 * 60)) - day * 24;
          minute = Math.floor(intDiff / 60) - day * 24 * 60 - hour * 60;
          second =
            Math.floor(intDiff) -
            day * 24 * 60 * 60 -
            hour * 60 * 60 -
            minute * 60;
          if (hour <= 9) hour = "0" + hour;
          if (minute <= 9) minute = "0" + minute;
          if (second <= 9) second = "0" + second;
          that.$set(that, "day", day);
          that.$set(that, "hour", hour);
          that.$set(that, "minute", minute);
          that.$set(that, "second", second);
        } else {
          that.day = "00";
          that.hour = "00";
          that.minute = "00";
          that.second = "00";
        }
      }
      runTime();
      setInterval(runTime, 1000);
    },
  },
};
</script>

<style>
.time {
  display: flex;
  justify-content: center;
}
.red {
  color: var(--view-theme);
  margin: 0 4rpx;
}
</style>
