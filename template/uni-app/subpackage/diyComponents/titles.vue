<template>
  <!-- tiêu đề -->
  <common-wrapper :config="configData" v-show="!isSortType">
    <view :style="[titleWrapStyle]">
      <view
        @click="goLink"
        class="title acea-row row-middle row-between"
        :style="[titleLocation]"
      >
        <view :style="[titleStyle]">{{ dataConfig.titleConfig.value }}</view>
        <view
          class="more"
          v-if="!dataConfig.buttonConfig.tabVal"
          :style="[moreStyle]"
        >
          {{ dataConfig.titleConfigRight.value }}
          <text class="iconfont icon-ic_rightarrow"></text>
        </view>
      </view>
    </view>
  </common-wrapper>
</template>

<script>
import commonWrapper from "./commonWrapper.vue";
export default {
  components: { commonWrapper },
  name: "titles",
  props: {
    dataConfig: {
      type: Object,
      default: () => {},
    },
    isSortType: {
      type: String | Number,
      default: 0,
    },
  },
  computed: {
    configData() {
      return {
        ...this.dataConfig,
        paddingConfig: this.dataConfig.paddingConfig || {
          isAll: false,
          valList: [
            {
              val: this.dataConfig.topConfig
                ? this.dataConfig.topConfig.val
                : 0,
            },
            {
              val: this.dataConfig.prConfig ? this.dataConfig.prConfig.val : 0,
            },
            {
              val: this.dataConfig.bottomConfig
                ? this.dataConfig.bottomConfig.val
                : 0,
            },
            {
              val: this.dataConfig.prConfig ? this.dataConfig.prConfig.val : 0,
            },
          ],
        },
        marginConfig: this.dataConfig.marginConfig || {
          isAll: false,
          valList: [
            {
              val: this.dataConfig.mbConfig ? this.dataConfig.mbConfig.val : 0,
            },
            {
              val: 0,
            },
            {
              val: 0,
            },
            {
              val: 0,
            },
          ],
        },
      };
    },
    titleWrapStyle() {
      const fillet = this.dataConfig.fillet || {};
      const filletVal = Number(fillet.val || 0);
      const filletList = Array.isArray(fillet.valList) ? fillet.valList : [];
      let borderRadius = `${filletVal * 2}rpx`;
      if (fillet.type && filletList.length >= 4) {
        borderRadius = `${(filletList[0].val || 0) * 2}rpx ${
          (filletList[1].val || 0) * 2
        }rpx ${(filletList[3].val || 0) * 2}rpx ${
          (filletList[2].val || 0) * 2
        }rpx`;
      }
      return {
        "border-radius": borderRadius,
        background: `linear-gradient(90deg, ${this.getColorItem(
          this.dataConfig.moduleColor,
          0,
          "#FFFFFF"
        )} 0%, ${this.getColorItem(this.dataConfig.moduleColor, 1, "#FFFFFF")} 100%)`,
      };
    },
    titleStyle() {
      let style = {
        "font-size": `${((this.dataConfig.fontSize || {}).val || 14) * 2}rpx`,
        color: this.getColorItem(this.dataConfig.themeColor, 0, "#333333"),
      };
      switch (this.dataConfig.textStyle.tabVal) {
        case 1:
          style["font-style"] = "italic";
          break;
        case 2:
          style["font-weight"] = "bold";
          break;
      }
      return style;
    },
    titleLocation() {
      if (this.dataConfig.buttonConfig.tabVal) {
        let style = {};
        switch (this.dataConfig.textPosition.tabVal) {
          case 1:
            style["justify-content"] = "center";
            break;
          case 2:
            style["justify-content"] = "flex-end";
            break;
        }
        return style;
      }
    },
    moreStyle() {
      return {
        "font-size": `${((this.dataConfig.buttonText || {}).val || 12) * 2}rpx`,
        color: this.getColorItem(this.dataConfig.buttonColor, 0, "#999999"),
      };
    },
  },
  methods: {
    getColorItem(colorConfig, idx, fallback) {
      if (!colorConfig || typeof colorConfig !== "object") return fallback;
      const list =
        colorConfig.color ||
        colorConfig["màu sắc"] ||
        colorConfig["颜色"] ||
        [];
      if (!Array.isArray(list)) return fallback;
      const item = list[idx];
      if (!item || typeof item !== "object") return fallback;
      return item.item || fallback;
    },
    goLink() {
      this.$util.JumpPath(this.dataConfig.linkConfig.value);
    },
  },
};
</script>

<style lang="scss">
.title {
  justify-content: space-between;
  padding: 26rpx 24rpx;
  border-radius: 16rpx 16rpx 0rpx 0rpx;
  font-weight: 500;
  font-size: 32rpx;
  line-height: 44rpx;
  color: #333333;

  .more {
    font-weight: 400;
    font-size: 24rpx;
    line-height: 34rpx;
    color: #999999;
  }

  .iconfont {
    font-size: 24rpx;
  }
}
</style>
