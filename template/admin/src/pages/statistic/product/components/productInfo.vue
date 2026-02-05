<template>
  <el-card :bordered="false" shadow="never" class="ivu-mt" v-loading="spinShow">
    <div class="acea-row row-between-wrapper mb20">
      <div class="statics-header-title">
        <h4>{{ $t('message.pages.statistic.product.productInfo.overview') }}</h4>
        <el-tooltip width="500" placement="right-start">
          <i class="el-icon-question ml10"></i>
          <div slot="content">
            <div>{{ $t('message.pages.statistic.product.productInfo.browseTipTitle') }}</div>
            <div>{{ $t('message.pages.statistic.product.productInfo.browseTipDesc') }}</div>
            <br />
            <div>{{ $t('message.pages.statistic.product.productInfo.visitorTipTitle') }}</div>
            <div>{{ $t('message.pages.statistic.product.productInfo.visitorTipDesc') }}</div>
            <br />
            <div>{{ $t('message.pages.statistic.product.productInfo.payCountTipTitle') }}</div>
            <div>{{ $t('message.pages.statistic.product.productInfo.payCountTipDesc') }}</div>
            <br />
            <div>{{ $t('message.pages.statistic.product.productInfo.payAmountTipTitle') }}</div>
            <div>{{ $t('message.pages.statistic.product.productInfo.payAmountTipDesc') }}</div>
            <br />
            <div>{{ $t('message.pages.statistic.product.productInfo.refundCountTipTitle') }}</div>
            <div>{{ $t('message.pages.statistic.product.productInfo.refundCountTipDesc') }}</div>
            <br />
            <div>{{ $t('message.pages.statistic.product.productInfo.refundAmountTipTitle') }}</div>
            <div>{{ $t('message.pages.statistic.product.productInfo.refundAmountTipDesc') }}</div>
          </div>
        </el-tooltip>
      </div>
      <div class="acea-row">
        <el-date-picker
          clearable
          v-model="timeVal"
          type="daterange"
          :editable="false"
          @change="onchangeTime"
          format="yyyy/MM/dd"
          value-format="yyyy/MM/dd"
          :start-placeholder="$t('message.pages.statistic.product.productInfo.startDate')"
          :end-placeholder="$t('message.pages.statistic.product.productInfo.endDate')"
          :picker-options="pickerOptions"
          style="width: 250px"
          class="mr20"
        ></el-date-picker>
        <el-button type="primary" v-db-click @click="onSeach">{{ $t('message.pages.statistic.product.productInfo.query') }}</el-button>
        <el-button type="primary" v-db-click @click="excel">{{ $t('message.pages.statistic.product.productInfo.export') }}</el-button>
      </div>
    </div>
    <div class="mb20">
      <el-row>
        <el-col v-bind="grid" class="acea-row mb30" v-for="(item, index) in list" :key="index">
          <div
            class="iconCrl mr15"
            :class="{ one: index % 4 == 0, two: index % 4 == 1, three: index % 4 == 2, four: index % 4 == 3 }"
          >
            <i :class="item.icon" class="iconfont"></i>
          </div>
          <div class="info">
            <span class="sp1" v-text="item.name"></span>
            <span class="sp2" v-if="index === list.length - 1" v-text="item.list.num"></span>
            <span class="sp2" v-else v-text="item.list.num"></span>
            <span class="content-time spBlock"
              >{{ $t('message.pages.statistic.product.productInfo.chainRatio') }}<i class="content-is" :class="Number(item.list.percent) >= 0 ? 'up' : 'down'"
                >{{ item.list.percent }}%</i
              >
              <i
                :style="{ color: Number(item.list.percent) >= 0 ? '#F5222D' : '#39C15B' }"
                :class="[Number(item.list.percent) >= 0 ? 'el-icon-caret-top' : 'el-icon-caret-bottom']"
              />
            </span>
          </div>
        </el-col>
      </el-row>
    </div>
    <echarts-new :option-data="optionData" :styles="style" height="100%" width="100%" v-if="optionData"></echarts-new>
  </el-card>
</template>

<script>
import { statisticBasicApi, statisticTrendApi, statisticProductExcel } from '@/api/statistic';
import echartsNew from '@/components/echartsNew/index';
import { formatDate } from '@/utils/validate';
export default {
  name: 'productInfo',
  components: {
    echartsNew,
  },
  data() {
    return {
      spinShow: false,
      grid: {
        xl: 4,
        lg: 8,
        md: 8,
        sm: 8,
        xs: 24,
      },
      pickerOptions: this.$timeOptions,
      name: '',
      timeVal: [],
      dataTime: '',
      list: [],
      optionData: {},
      style: { height: '400px' },
    };
  },
  created() {
    this.name = this.$t('message.pages.statistic.product.productInfo.last30Days');
    const end = new Date();
    const start = new Date();
    start.setTime(start.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 29)));
    this.timeVal = [start, end];
    this.dataTime = formatDate(start, 'yyyy/MM/dd') + '-' + formatDate(end, 'yyyy/MM/dd');
  },
  mounted() {
    this.getStatistics();
    this.getTrend();
  },
  methods: {
    // 导出
    excel() {
      statisticProductExcel({ data: this.dataTime }).then(async (res) => {
        res.data.url.map((item) => {
          window.location.href = item;
        });
      });
    },
    onSeach() {
      this.getStatistics();
      this.getTrend();
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.dataTime = this.timeVal ? this.timeVal.join('-') : '';
    },
    // 统计
    getStatistics() {
      statisticBasicApi({ data: this.dataTime })
        .then(async (res) => {
          const cardLists = res.data;
          this.list = [
            {
              name: this.$t('message.pages.statistic.product.productInfo.browse'),
              icon: 'iconshangpinliulanliang',
              list: cardLists.browse,
            },
            {
              name: this.$t('message.pages.statistic.product.productInfo.visitor'),
              icon: 'iconshangpinfangkeshu',
              list: cardLists.user,
            },
            {
              name: this.$t('message.pages.statistic.product.productInfo.payCount'),
              icon: 'iconzhifujianshu',
              list: cardLists.pay,
            },
            {
              name: this.$t('message.pages.statistic.product.productInfo.payAmount'),
              icon: 'iconzhifujine',
              list: cardLists.payPrice,
            },
            {
              name: this.$t('message.pages.statistic.product.productInfo.refundCount'),
              icon: 'icontuikuanjianshu',
              list: cardLists.refund,
            },
            {
              name: this.$t('message.pages.statistic.product.productInfo.refundAmount'),
              icon: 'icontuikuan',
              list: cardLists.refundPrice,
            },
          ];
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 统计图
    getTrend() {
      this.spinShow = true;
      statisticTrendApi({ data: this.dataTime })
        .then(async (res) => {
          let legend = res.data.series.map((item) => {
            return item.name;
          });
          let xAxis = res.data.xAxis;
          let col = ['#B37FEB', '#FFAB2B', '#1890FF', '#00C050'];
          res.data.series.map((item, index) => {
            item.itemStyle = {
              normal: {
                color: col[index],
              },
            };
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
            yAxis: [
              {
                type: 'value',
                name: this.$t('message.pages.statistic.product.productInfo.amount'),
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
              {
                type: 'value',
                name: this.$t('message.pages.statistic.product.productInfo.quantity'),
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
                // axisLabel: {
                //     formatter: '{value} °C'
                // }
              },
            ],
            series: res.data.series,
          };
          this.spinShow = false;
        })
        .catch((res) => {
          this.$message.error(res.msg);
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
  @media screen and (max-width: 1300px) {
    width: 25%;
  }
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
