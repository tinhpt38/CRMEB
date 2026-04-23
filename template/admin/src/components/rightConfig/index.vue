<template>
  <div class="right-box" v-if="rCom.length">
    <div class="title-bar">Cấu hình mô-đun</div>
    <div class="mobile-config" v-if="rCom.length">
      <div v-for="(item, key) in rCom" :key="key">
        <component
          :is="item.components.name"
          :moduleName="name.name"
          :name="item.configNme"
          :configData="configData"
          :configNum="configNum"
        ></component>
      </div>
      <div style="text-align: center" v-if="rCom.length">
        <el-button type="primary" style="width: 100%; margin: 0 auto; height: 40px" v-db-click @click="saveConfig"
          >cứu</el-button
        >
      </div>
    </div>
  </div>
</template>

<script>
import { getCategory, getByCategory, diySave, storeStatus } from '@/api/diy';
import toolCom from '@/components/diyComponents/index.js';
import { mapMutations } from 'vuex';
import { mapState } from 'vuex';
export default {
  name: 'rightConfig',
  components: {
    ...toolCom,
  },
  props: {
    name: {
      type: Object,
      default: {},
    },
    pageId: {
      type: Number,
      default: 0,
    },
    configNum: {
      type: Number | String,
      default: 'default',
    },
  },
  computed: {
    // ...mapState({
    //     defultArr:(state)=>state.goodSelect.component,
    // })
    defultArr() {
      return this.$store.state.moren.component;
    },
  },
  watch: {
    name: {
      handler(nVal, oVal) {
        this.rCom = [];
        this.configData = this.$store.state.moren.defaultConfig[nVal.name];
        if (!this.configData.hasOwnProperty(this.configNum)) {
          let defaultObj = JSON.parse(JSON.stringify(this.configData.defaultVal));
          this.configData[nVal.num] = defaultObj;
          this.$store.commit('moren/upDataName', this.configData);
        }
        let that = this;
        setTimeout(function () {
          that.rCom = that.$store.state.moren.component[nVal.name].list;
        }, 30);
        if (this.configData[nVal.num].selectConfig) {
          let type = this.configData[nVal.num].selectConfig.type ? this.configData[nVal.num].selectConfig.type : 0;
          if (type) {
            this.getByCategory();
          } else {
            this.getCategory();
          }
        }
      },
      deep: true,
    },
    defultArr: {
      handler(nVal, oVal) {
        this.rCom = [];
        let tempArr = this.objToArray(nVal);
        this.rCom = nVal[this.name.name].list;
      },
      deep: true,
    },
  },
  data() {
    return {
      rCom: [],
      configData: {},
      isShow: true,
      categoryList: [],
      status: 0,
    };
  },
  mounted() {
    this.storeStatus();
  },
  methods: {
    storeStatus() {
      storeStatus().then((res) => {
        this.status = parseInt(res.data.store_status);
      });
    },
    getCategory() {
      getCategory().then((res) => {
        let data = [];
        res.data.map((item) => {
          data.push({
            title: item.title,
            pid: item.pid,
            activeValue: item.id.toString(),
          });
        });
        this.configData[this.name.num].selectConfig.list = data;
        this.bus.$emit('upData', data);
      });
    },
    //Nhận phân loại thứ cấp
    getByCategory() {
      getByCategory().then((res) => {
        let data = [];
        res.data.map((item) => {
          data.push({
            title: item.cate_name,
            pid: item.pid,
            activeValue: item.id.toString(),
          });
        });
        this.configData[this.name.num].selectConfig.list = data;
        this.bus.$emit('upData', data);
      });
    },
    // lưu dữ liệu
    saveConfig() {
      let data = this.$store.state.moren.defaultConfig;
      if (this.name.name == 'tabBar') {
        if (!this.status) {
          let list = data.tabBar.default.tabBarList.list;
          for (let i = 0; i < list.length; i++) {
            if (list[i].link == '/pages/storeList/index' || list[i].link == 'pages/storeList/index') {
              return this.$message.error('Vui lòng kích hoạt các chức năng ngoại vi của bạn trước(/pages/storeList/index)');
            }
          }
        }
        if (data.tabBar.default.tabBarList.list.length < 2) {
          return this.$message.error('Bạn nên thêm ít nhất 2 điều hướng');
        }
      }

      diySave(this.pageId, {
        value: data,
      }).then((res) => {
        this.$message.success('Đã lưu thành công');
      });
    },
    // Đối tượng vào mảng
    objToArray(array) {
      var arr = [];
      for (var i in array) {
        arr.push(array[i]);
      }
      return arr;
    },
  },
};
</script>

<style lang="scss" scoped>
.right-box {
  width: 700px;
  margin-left: 50px;
  border: 1px solid #ddd;
  border-radius: 4px;
  height: 700px;
  overflow-y: scroll;
  &::-webkit-scrollbar {
    /* Phong cách tổng thể của thanh cuộn */
    width: 4px; /* Chiều cao và chiều rộng tương ứng với kích thước của thanh cuộn ngang và dọc. */
    height: 1px;
  }
  &::-webkit-scrollbar-thumb {
    /* Ô vuông nhỏ bên trong thanh cuộn */
    border-radius: 4px;
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
    background: #535353;
  }
  &::-webkit-scrollbar-track {
    /* theo dõi bên trong thanh cuộn */
    box-shadow: inset 0 0 5px #fff;
    border-radius: 4px;
    background: #fff;
  }
}
.title-bar {
  width: 100%;
  height: 38px;
  line-height: 38px;
  padding-left: 24px;
  color: #333;
  border-radius: 4px;
  border-bottom: 1px solid #eee;
}
</style>
