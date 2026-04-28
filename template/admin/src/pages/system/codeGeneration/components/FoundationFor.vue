<template>
  <div class="main">
    <el-alert class="mb20" closable>
      <template v-slot:title>CrudHướng dẫn xây dựng</template>
      <template> Không thể tạo các bảng đi kèm với hệ thống; các bảng đã được tạo có thể tiếp tục được tạo. </template>
    </el-alert>
    <el-form ref="foundation" :model="foundation" :rules="foundationRules" label-width="100px">
      <el-form-item label="Thực đơn：">
        <el-cascader
          class="form-width"
          v-model="foundation.pid"
          size="small"
          :options="menusList"
          :props="{ checkStrictly: true, multiple: false, emitPath: false }"
          clearable
        ></el-cascader>
        <div class="tip">Tùy chọn, menu đã chọn sẽ tự động được ghi vào menu này sau khi được chọn thành công.</div>
      </el-form-item>
      <el-form-item label="Tên thực đơn：">
        <el-input class="form-width" v-model="foundation.menuName" placeholder="Vui lòng nhập tên thực đơn"></el-input>
        <div class="tip">
          Menu được tạo là tùy chọn. Nếu không điền, tên menu được tạo sẽ mặc định là tên bảng. Sau khi tạo, các quyền được tạo tự động sẽ được thêm vào menu theo mặc định.
        </div>
      </el-form-item>
      <el-form-item label="Tên mô-đun：" prop="modelName">
        <el-input class="form-width" v-model="foundation.modelName" placeholder="Vui lòng nhập tên mô-đun"></el-input>
        <div class="tip">Tên mô-đun bằng tiếng Trung hoặc tiếng Anh và được sử dụng trong tiền tố tên giao diện và tiêu đề tiêu đề biểu mẫu.</div>
      </el-form-item>
      <el-form-item label="Tên bảng：" prop="tableName">
        <el-input class="form-width" v-model="foundation.tableName" placeholder="Vui lòng nhập tên bảng"></el-input>
        <div class="tip">
          Nó được sử dụng để tạo tên bảng do CRUD chỉ định và không cần mang tiền tố bảng; bảng đã tạo sẽ không được tạo lại; hoặc tập tin tương ứng có thể bị xóa và tạo lại! Các bảng dữ liệu quan trọng trong hệ thống tương ứng sẽ không được phép tạo.！
        </div>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { crudMenus, crudColumnType, crudFilePath } from '@/api/systemCodeGeneration';

export default {
  name: '',
  props: {
    foundation: {
      type: Object,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {
      foundationRules: {
        // pid: [{ required: true, message: 'Vui lòng nhập thực đơn', trigger: 'blur' }],
        tableName: [{ required: true, message: 'Vui lòng nhập tên bảng', trigger: 'blur' }],
        modelName: [{ required: true, message: 'Vui lòng nhập tên mô-đun', trigger: 'blur' }],
      },
      menusList: [],
      columnTypeList: [],
      fromTypeList: [
        {
          value: '0',
          label: 'Không được tạo',
        },
        {
          value: 'input',
          label: 'input',
        },
        {
          value: 'textarea',
          label: 'textarea',
        },
        // {
        //   value: 'select',
        //   label: 'select',
        // },
        {
          value: 'radio',
          label: 'radio',
        },
        {
          value: 'number',
          label: 'number',
        },
        {
          value: 'frameImageOne',
          label: 'frameImageOne',
        },
        {
          value: 'frameImages',
          label: 'frameImages',
        },
      ],
      loading: false,
      tableField: [],
    };
  },
  created() {
    this.getCrudMenus();
  },
  mounted() {},
  methods: {
    disabledInput(index) {
      let fieldInfo = this.tableField[index];
      let res = ['addTimestamps', 'addSoftDelete'].includes(this.tableField[index].field_type);
      if (fieldInfo.primaryKey) {
        res = true;
      }
      if (fieldInfo.field === 'delete_time' && fieldInfo.field_type === 'timestamp') {
        res = true;
      }
      return res;
    },
    initfield() {
      this.tableField = [];
    },
    changeItemField(e, i) {
      if (e === 'addSoftDelete') {
        this.$set(this.tableField[i], 'comment', 'Xóa giả');
      }
      if (e === 'addTimestamps') {
        this.$set(this.tableField[i], 'comment', 'Thêm và sửa đổi thời gian');
      }
    },
    getCrudMenus() {
      crudMenus().then((res) => {
        this.menusList = res.data;
      });
      crudColumnType().then((res) => {
        this.columnTypeList = res.data.types;
      });
    },
    del(index) {
      this.tableField.splice(index, 1);
    },
  },
};
</script>
<style lang="scss" scoped>
.form-width {
  width: 500px;
}
.item {
  display: flex;
  margin-bottom: 10px;
  .row {
    width: 140px;
    margin-right: 10px;
  }
}
</style>
