<template>
  <div>
    <pages-header ref="pageHeader" :title="$route.meta.title"></pages-header>
    <el-card :bordered="false" shadow="never" class="mt16">
      <el-form :model="formData" label-width="100px">
        <el-form-item label="Xổ số điểm：">
          <el-select v-model="formData.point" clearable>
            <el-option v-for="(item, j) in list.point" :value="item.id" :key="item.id" :label="item.name"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Trả tiền rút thăm：">
          <el-select v-model="formData.pay" clearable>
            <el-option v-for="(item, j) in list.pay" :value="item.id" :key="item.id" :label="item.name"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Đánh giá xổ số：">
          <el-select v-model="formData.evaluate" clearable>
            <el-option
              v-for="(item, j) in list.evaluate"
              :value="item.id"
              :key="item.id"
              :label="item.name"
            ></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" v-db-click @click="save">cứu</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { factorListApi, factorUseApi } from '@/api/lottery';
export default {
  name: 'lotteryConfig',
  data() {
    return {
      formData: {
        evaluate: '', // Trả tiền cho các đánh giá
        pay: '', // chi trả
        point: '', // tích phân
      },
      list: {
        evaluate: [], // Trả tiền cho các đánh giá
        pay: [], // chi trả
        point: [], // tích phân
      },
    };
  },
  computed: {},
  created() {
    this.getList();
  },
  methods: {
    getList() {
      factorListApi().then((res) => {
        this.list = res.data;
        this.formData = res.data.info;
      });
    },
    save() {
      factorUseApi(this.formData).then((res) => {
        this.$message.success(res.msg);
      });
    },
  },
};
</script>

<style scoped lang="scss">
.content_width {
  width: 414px;
}

.info {
  color: #888;
  font-size: 12px;
}
</style>
