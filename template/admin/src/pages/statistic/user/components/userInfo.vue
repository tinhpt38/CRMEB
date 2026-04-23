<template>
  <el-card :bordered="false" shadow="never" class="ivu-mt-16" v-loading="spinShow">
    <div class="acea-row row-between-wrapper">
      <div class="statics-header-title mb20">
        <h4>Hồ sơ người dùng</h4>
        <el-tooltip placement="right-start">
          <i class="el-icon-question ml10"></i>
          <div slot="content">
            <div>Số lượng người dùng tích lũy</div>
            <div>Tổng số người dùng của trung tâm mua sắm</div>
            <br />
            <div>Số lượng khách truy cập</div>
            <div>Số người được loại bỏ trùng lặp đã truy cập trang trung tâm mua sắm theo các điều kiện đã chọn</div>
            <br />
            <div>Lượt xem</div>
            <div>Số lần người dùng duyệt trang trung tâm mua sắm trong các điều kiện đã chọn. Được ghi lại một lần mỗi khi mở trang hoặc mỗi khi trang được làm mới</div>
            <br />
            <div>Số lượng người dùng mới</div>
            <div>Trong các điều kiện đã chọn, người dùng mới đăng ký</div>
            <br />
            <div>Số lượng người dùng đã thực hiện giao dịch</div>
            <div>Người dùng đặt hàng và thanh toán thành công theo các điều kiện đã chọn</div>
            <br />
            <div>Số lượng thành viên trả phí</div>
            <div>Số lượng người dùng có tư cách thành viên trả phí tại trung tâm thương mại vào cuối thời gian chiếu</div>
          </div>
        </el-tooltip>
      </div>
    </div>
    <div class="mb20">
      <el-row>
        <el-col v-bind="grid" v-for="(item, index) in list" :key="index">
          <div class="acea-row mb30 fwn">
            <div class="iconCrl mr15" :class="item.colors">
              <i class="iconfont" :class="item.icon"></i>
            </div>
            <div class="info">
              <span class="sp1" v-text="item.name"></span>
              <span class="sp2" v-if="index === list.length - 1" v-text="item.list.num"></span>
              <span class="sp2" v-else v-text="item.list.num"></span>
              <span class="content-time spBlock"
                >tăng trưởng hàng tháng：<i class="content-is" :class="Number(item.list.percent) >= 0 ? 'up' : 'down'"
                  >{{ item.list.percent }}%</i
                >
                <i
                  :style="{ color: Number(item.list.percent) >= 0 ? '#F5222D' : '#39C15B' }"
                  :class="[Number(item.list.percent) >= 0 ? 'el-icon-caret-top' : 'el-icon-caret-bottom']"
                />
              </span>
            </div>
          </div>
        </el-col>
      </el-row>
    </div>
    <echarts-new :option-data="optionData" :styles="style" height="100%" width="100%" v-if="optionData"></echarts-new>
  </el-card>
</template>

<script>
import { statisticUserBasicApi, statisticUserTrendApi } from '@/api/statistic';
import echartsNew from '@/components/echartsNew/index';
export default {
  name: 'userInfo',
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
        xl: 4,
        lg: 4,
        md: 12,
        sm: 24,
        xs: 24,
      },
      name: '30 ngày qua',
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
    onSeach() {
      this.getStatistics();
      this.getTrend();
    },
    // ngày cụ thể
    onchangeTime(e) {
      this.timeVal = e;
      this.dataTime = this.timeVal ? this.timeVal.join('-') : '';
      this.name = this.dataTime;
    },
    // thống kê
    getStatistics() {
      statisticUserBasicApi(this.formInline)
        .then(async (res) => {
          const cardLists = res.data;
          this.list = [
            {
              name: 'Người dùng tích lũy',
              icon: 'iconleijiyonghu',
              list: cardLists.cumulativeUser,
              colors: 'four',
            },
            {
              name: 'Số lượng khách truy cập',
              icon: 'iconfangkeshu',
              list: cardLists.people,
              colors: 'one',
            },
            {
              name: 'Lượt xem',
              icon: 'iconshangpinliulanliang',
              list: cardLists.browse,
              colors: 'two',
            },
            {
              name: 'Số lượng người dùng mới',
              icon: 'iconxinzengyonghushu',
              list: cardLists.newUser,
              colors: 'three',
            },
            {
              name: 'Số lượng người dùng đã thực hiện giao dịch',
              icon: 'iconchengjiaoyonghushu',
              list: cardLists.payPeople,
              colors: 'four',
            },
            {
              name: 'Số lượng thành viên trả phí',
              icon: 'iconfufeihuiyuanshu',
              list: cardLists.payUser,
              colors: 'four',
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
      statisticUserTrendApi(this.formInline)
        .then(async (res) => {
          let legend = res.data.series.map((item) => {
            return item.name;
          });
          let xAxis = res.data.xAxis;
          let col = ['#5B8FF9', '#5AD8A6', '#FFAB2B', '#5D7092'];
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
.iconfont {
  font-size: 16px;
  color: #fff;
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
.iconCrl {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  text-align: center;
  line-height: 32px;
  opacity: 0.7;
  /*margin-left: 74px;*/
}

.lan {
  background: var(--prev-color-primary);
}

.iconshangpinliulanliang {
  color: #fff;
}

.infoBox {
  width: 20%;
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
.fwn {
  flex-wrap: nowrap;
}
</style>
