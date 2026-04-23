<template>
  <el-card :bordered="false" shadow="never" class="ivu-mt-16" v-loading="spinShow">
    <div class="acea-row row-between-wrapper">
      <div class="statics-header-title mb20">
        Tóm tắt người dùng tài khoản công cộng
        <el-tooltip effect="light" word-wrap width="500" trigger="hover" placement="right-start">
          <i class="el-icon-info"></i>
          <div slot="content">
            <div>Số lượng người dùng mới theo dõi</div>
            <div>Trong các điều kiện đã chọn, số lượng người dùng theo dõi tài khoản chính thức, bao gồm cả người dùng theo dõi lần đầu và những người theo dõi lại</div>
            <br />
            <div>Số lượng người dùng mới được bỏ chặn</div>
            <div>Số người dùng đã hủy theo dõi tài khoản chính thức theo các điều kiện đã chọn</div>
            <br />
            <div>Người dùng được thêm ròng</div>
            <div>Trong các điều kiện đã chọn, số lượng người dùng mới theo dõi - số lượng người dùng mới hủy theo dõi</div>
            <br />
            <div>Số lượng người dùng theo dõi tích lũy</div>
            <div>Số lượng người dùng theo dõi tài khoản chính thức khi hết thời gian sàng lọc</div>
            <br />
            <div>Số lượng người dùng được bỏ chặn tích lũy</div>
            <div>Số người dùng hủy theo dõi tài khoản chính thức khi hết thời gian sàng lọc</div>
          </div>
        </el-tooltip>
      </div>
    </div>
    <div class="acea-row mb20">
      <div class="infoBox acea-row mb30" v-for="(item, index) in list" :key="index">
        <div
          class="iconCrl mr15"
          :class="{ one: index % 4 == 0, two: index % 4 == 1, three: index % 4 == 2, four: index % 4 == 3 }"
        >
          <i class="iconfont" :class="item.icon"></i>
        </div>
        <div class="info">
          <span class="sp1" v-text="item.name"></span>
          <span class="sp2" v-if="index === list.length - 1" v-text="item.list.num"></span>
          <span class="sp2" v-else v-text="item.list.num"></span>
          <span class="content-time spBlock"
            >tăng trưởng hàng tháng：<i class="content-is" :class="Number(item.list.percent) >= 0 ? 'up' : 'down'"
              >{{ Number(item.list.percent).toFixed(2) }}%</i
            ><Icon
              :color="Number(item.list.percent) >= 0 ? '#F5222D' : '#39C15B'"
              :type="Number(item.list.percent) >= 0 ? 'md-arrow-dropup' : 'md-arrow-dropdown'"
          /></span>
        </div>
      </div>
    </div>
    <echarts-new :option-data="optionData" :styles="style" height="100%" width="100%" v-if="optionData"></echarts-new>
  </el-card>
</template>

<script>
import { statisticWechatApi, statisticWechatTrendApi } from '@/api/statistic';
import echartsNew from '@/components/echartsNew/index';
export default {
  name: 'wechetInfo',
  components: {
    echartsNew,
  },
  props: {
    formInline: {
      type: Object,
      default: function () {
        return {
          channel_type: '',
          data: '',
        };
      },
    },
  },
  data() {
    return {
      spinShow: false,
      grid: {
        xl: 8,
        lg: 8,
        md: 8,
        sm: 24,
        xs: 24,
      },
      timeVal: [],
      dataTime: '',
      list: [],
      optionData: {},
      style: { height: '400px' },
    };
  },
  mounted() {
    this.getStatistics();
    this.getTrend();
  },
  methods: {
    // ngày cụ thể
    onchangeTime(e) {
      this.timeVal = e;
      this.dataTime = this.timeVal ? this.timeVal.join('-') : '';
      this.name = this.dataTime;
      this.getStatistics();
      this.getTrend();
      // this.userFrom.user_time = this.timeVal ? this.timeVal.join('-') : ''
    },
    // thống kê
    getStatistics() {
      statisticWechatApi(this.formInline)
        .then(async (res) => {
          const cardLists = res.data;
          this.list = [
            {
              name: 'Số lượng người dùng mới theo dõi',
              icon: 'iconxinzengguanzhuyonghu',
              list: cardLists.subscribe,
            },
            {
              name: 'Số lượng người dùng mới được bỏ chặn',
              icon: 'iconxinzengquguanyonghu',
              list: cardLists.unSubscribe,
            },
            {
              name: 'Người dùng được thêm ròng',
              icon: 'iconjingzengyonghu',
              list: cardLists.increaseSubscribe,
            },
            {
              name: 'Số lượng người dùng theo dõi tích lũy',
              icon: 'iconleijiguanzhuyonghu',
              list: cardLists.cumulativeSubscribe,
            },
            {
              name: 'Số lượng người dùng được bỏ chặn tích lũy',
              icon: 'iconleijiquguanyonghu',
              list: cardLists.cumulativeUnSubscribe,
            },
          ];
        })
        .catch((res) => {
          this.$message.error(res);
        });
    },
    // Biểu đồ thống kê
    getTrend() {
      this.spinShow = true;
      statisticWechatTrendApi(this.formInline)
        .then(async (res) => {
          let legend = res.data.series.map((item) => {
            return item.name;
          });
          let xAxis = res.data.xAxis;
          let col = ['#5B8FF9', '#5AD8A6', '#5D7092', '#5D7092'];
          let series = [];
          res.data.series.map((item, index) => {
            series.push({
              name: item.name,
              type: 'line',
              data: item.value,
              itemStyle: {
                normal: {
                  color: col[index],
                },
              },
              smooth: true,
            });
          });
          this.optionData = {
            tooltip: {
              trigger: 'axis',
              axisPointer: {
                type: 'cross',
                label: {
                  backgroundColor: '#6a7985',
                },
              },
            },
            legend: {
              x: 'center',
              data: legend,
            },
            grid: {
              left: '3%',
              right: '4%',
              bottom: '3%',
              containLabel: true,
            },
            toolbox: {
              feature: {
                saveAsImage: {},
              },
            },
            xAxis: {
              type: 'category',
              boundaryGap: true,
              // axisTick:{
              //     show:false
              // },
              // axisLine:{
              //     show:false
              // },
              // splitLine: {
              //     show: false
              // },
              axisLabel: {
                interval: 0,
                rotate: 40,
                textStyle: {
                  color: '#000000',
                },
              },
              data: xAxis,
            },
            yAxis: {
              type: 'value',
              axisLine: {
                show: false,
              },
              axisTick: {
                show: false,
              },
              axisLabel: {
                textStyle: {
                  color: '#7F8B9C',
                },
              },
              splitLine: {
                show: true,
                lineStyle: {
                  color: '#F5F7F9',
                },
              },
            },
            series: series,
          };
          this.spinShow = false;
        })
        .catch((res) => {
          this.$message.error(res);
          this.spinShow = false;
        });
    },
  },
};
</script>

<style scoped lang="scss">
.one {
  background: var(--prev-color-primary);
}
.two {
  background: #00c050;
}
.three {
  background: #ffab2b;
}
.four {
  background: #b37feb;
}
.up,
.el-icon-caret-top {
  color: #f5222d;
  font-size: 12px;
  opacity: 1 !important;
}

.down,
.el-icon-caret-bottom {
  color: #39c15b;
  font-size: 12px;
}
.curP {
  cursor: pointer;
}
.header {
  &-title {
    font-size: 16px;
    color: rgba(0, 0, 0, 0.85);
  }
  &-time {
    font-size: 12px;
    color: #000000;
    opacity: 0.45;
  }
}

.iconfont {
  font-size: 16px;
  color: #fff;
}

.iconCrl {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  text-align: center;
  line-height: 32px;
  opacity: 0.7;
}

.lan {
  background: var(--prev-color-primary);
}

.iconshangpinliulanliang {
  color: #fff;
}

.infoBox {
  width: 20%;
  @media screen and (max-width: 1200px) {
    width: 33%;
  }
  @media screen and (max-width: 900px) {
    width: 50%;
  }
}

.info {
  .sp1 {
    color: #666;
    font-size: 14px;
    display: block;
  }
  .sp2 {
    font-weight: 400;
    font-size: 30px;
    color: rgba(0, 0, 0, 0.85);
    display: block;
  }
  .sp3 {
    font-size: 12px;
    font-weight: 400;
    color: rgba(0, 0, 0, 0.45);
    display: block;
  }
}
</style>
