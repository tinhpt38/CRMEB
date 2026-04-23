<template>
  <el-row :gutter="16">
    <el-col :xs="24" :sm="24" :md="24" :lg="18">
      <el-card :bordered="false" shadow="never" class="ivu-mt-16">
        <div class="acea-row row-between-wrapper">
          <h4 class="statics-header-title mb20">Phân bố địa lý của người dùng</h4>
        </div>
        <el-row>
          <el-col :xs="24" :sm="24" :md="24" :lg="10">
            <div class="echarts">
              <div :style="{ height: '400px', width: '100%' }" ref="myEchart"></div>
            </div>
          </el-col>
          <el-col :xs="24" :sm="24" :md="24" :lg="14">
            <div class="tables">
              <el-table height="400" :columns="columns1" :data="resdataList">
                <el-table-column :label="item.title" :min-width="100" v-for="(item, index) in columns1" :key="index">
                  <template slot-scope="scope">
                    <template v-if="item.key">
                      <div>
                        <span>{{ scope.row[item.key] }}</span>
                      </div>
                    </template>
                  </template>
                </el-table-column>
              </el-table>
            </div>
          </el-col>
        </el-row>
      </el-card>
    </el-col>
    <el-col :xs="24" :sm="24" :md="24" :lg="6">
      <el-card :bordered="false" shadow="never" class="ivu-mt-16">
        <div class="acea-row row-between-wrapper">
          <h4 class="statics-header-title mb20">Tỷ lệ giới tính người dùng</h4>
        </div>
        <echarts-new
          :option-data="optionData"
          :styles="style"
          height="100%"
          width="100%"
          v-if="optionData"
        ></echarts-new>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
import echarts from 'echarts';
import '../../../../../node_modules/echarts/map/js/china.js'; // Giới thiệu dữ liệu bản đồ Trung Quốc
import { statisticWechatRegionApi, statisticWechatSexApi } from '@/api/statistic';
import echartsNew from '@/components/echartsNew/index';
export default {
  name: 'userRegion',
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
      chart: null,
      resdata: [],
      resdataList: [],
      columns1: [
        {
          title: 'TOPtỉnh',
          key: 'province',
        },
        {
          title: 'Số lượng người dùng tích lũy',
          key: 'allNum',
          sortable: true,
        },
        {
          title: 'Số lượng người dùng mới',
          key: 'newNum',
          sortable: true,
        },
        {
          title: 'Số lượng khách truy cập',
          key: 'visitNum',
          sortable: true,
        },
        {
          title: 'Số tiền thanh toán',
          key: 'payPrice',
          sortable: true,
        },
      ],
      style: { height: '400px' },
      optionData: {},
    };
  },
  mounted() {
    this.getTrend();
    this.getSex();
  },
  beforeDestroy() {
    if (!this.chart) {
      return;
    }
    this.chart.dispose();
    this.chart = null;
  },
  methods: {
    chinaConfigure() {
      if (this.chart) {
        this.chart.dispose();
      }
      this.$nextTick(() => {
        let myChart = echarts.init(this.$refs.myEchart); //Đây là để có được vị trí của container
        this.chart = myChart;
        window.onresize = myChart.resize;
        myChart.setOption({
          // Thực hiện các cấu hình liên quan
          backgroundColor: '#fff',
          tooltip: {
            trigger: 'item',
            formatter: function (params) {
              return params.data
                ? `khu vực:${params.name}</br>Người dùng tích lũy: ${params.data.value}</br>Thêm người dùng mới: ${params.data.newNum}</br>Số lượng khách truy cập: ${params.data.visitNum}</br>Số tiền thanh toán: ${params.data.payPrice}`
                : `khu vực:${params.name}</br>Người dùng tích lũy: 0</br>Thêm người dùng mới: 0</br>Số lượng khách truy cập: 0</br>Số tiền thanh toán: 0`;
            },
          }, // Di chuyển chuột đến hộp nhắc nổi bên trong ảnh
          dataRange: {
            show: false,
            min: 0,
            max: 1000,
            text: ['High', 'Low'],
            realtime: true,
            calculable: true,
            color: ['orangered', 'yellow', 'lightskyblue'],
          },
          geo: {
            // Đây là khu vực cấu hình chính
            map: 'china', // Thể hiện bản đồ Trung Quốc
            roam: false,
            label: {
              normal: {
                show: false, // Có hiển thị tên địa điểm tương ứng hay không
                textStyle: {
                  color: 'rgba(0,0,0,0.4)',
                },
              },
            },
            itemStyle: {
              normal: {
                borderColor: 'rgba(0, 0, 0, 0.2)',
              },
              emphasis: {
                areaColor: null,
                shadowOffsetX: 0,
                shadowOffsetY: 0,
                shadowBlur: 20,
                borderWidth: 0,
                shadowColor: 'rgba(0, 0, 0, 0.5)',
              },
            },
          },
          series: [
            {
              type: 'scatter',
              zoom: 1.2,
              aspectScale: 1.75, //tỷ lệ khung hình
              coordinateSystem: 'geo', // Tương ứng với cấu hình trên
            },
            {
              type: 'map',
              geoIndex: 0,
              data: this.resdata,
            },
          ],
        });
      });
    },
    // Biểu đồ thống kê
    getTrend() {
      statisticWechatRegionApi(this.formInline)
        .then(async (res) => {
          this.resdataList = res.data;
          this.resdata = res.data.map((item) => {
            let jsonData = {};
            jsonData.name = item.province.replace('Tỉnh', '');
            jsonData.value = item.allNum;
            jsonData.newNum = item.newNum;
            jsonData.payPrice = item.payPrice;
            jsonData.visitNum = item.visitNum;
            return jsonData;
          });
          this.chinaConfigure();
        })
        .catch((res) => {
          this.$message.error(res);
        });
    },
    //giới tính
    getSex() {
      statisticWechatSexApi(this.formInline)
        .then(async (res) => {
          let totalSumAll = 0;
          res.data.forEach((item) => {
            totalSumAll += item.value;
          });
          this.optionData = {
            title: {
              show: true,
              text: 'Tổng số người dùng', // Hiện đang được viết cho đến chết
              subtext: totalSumAll, // Hiện đang được viết cho đến chết
              x: 'center',
              y: 'center',
              textStyle: {
                fontSize: '14',
                color: '#666666',
              },
              subtextStyle: {
                fontSize: '30',
                fontWeight: 'bold',
                color: '#333333',
              },
            },
            tooltip: {
              trigger: 'item',
              formatter: '{a} <br/>{b}: {c} ({d}%)',
            },
            legend: {
              orient: 'vertical',
              left: 10,
              data: ['không rõ', 'nam giới', 'nữ giới'],
            },
            series: [
              {
                name: 'Truy cập nguồn',
                type: 'pie',
                radius: ['50%', '70%'],
                avoidLabelOverlap: false,
                label: {
                  show: false,
                  position: 'center',
                },
                labelLine: {
                  show: false,
                },
                data: res.data,
                itemStyle: {
                  emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)',
                  },
                  normal: {
                    color: function (params) {
                      //Màu tùy chỉnh
                      var colorList = ['#999999', '#1890FF', '#FFAB2B'];
                      return colorList[params.dataIndex];
                    },
                  },
                },
              },
            ],
          };
        })
        .catch((res) => {
          this.$message.error(res);
        });
    },
  },
};
</script>

<style scoped lang="scss">
.echarts {
  width: 100%;
}
.tables {
  width: 100%;
  ::v-deep .ivu-table-overflowY {
    &::-webkit-scrollbar {
      width: 0;
    }
    &::-webkit-scrollbar-track {
      background-color: transparent;
    }
    &::-webkit-scrollbar-thumb {
      background: #e8eaec;
    }
  }
}
</style>
