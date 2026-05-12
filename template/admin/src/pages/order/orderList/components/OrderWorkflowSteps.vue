<template>
  <div class="order-workflow-steps">
    <div
      v-for="(step, index) in steps"
      :key="`${step.label}-${index}`"
      class="order-workflow-steps__item"
      :class="`is-${step.state}`"
    >
      <span class="order-workflow-steps__dot" />
      <span class="order-workflow-steps__label">{{ step.label }}</span>
      <span v-if="index < steps.length - 1" class="order-workflow-steps__line" />
    </div>
  </div>
</template>

<script>
import { buildOrderWorkflowSteps } from '@/utils/orderWorkflow';

export default {
  name: 'OrderWorkflowSteps',
  props: {
    row: {
      type: Object,
      default: () => ({}),
    },
  },
  computed: {
    steps() {
      return buildOrderWorkflowSteps(this.row);
    },
  },
};
</script>

<style scoped lang="scss">
.order-workflow-steps {
  display: flex;
  flex-wrap: wrap;
  gap: 4px 0;
}

.order-workflow-steps__item {
  position: relative;
  display: inline-flex;
  align-items: center;
  max-width: 100%;
  padding-right: 14px;
  margin-right: 6px;
}

.order-workflow-steps__dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #dcdfe6;
  flex-shrink: 0;
}

.order-workflow-steps__label {
  margin-left: 6px;
  font-size: 12px;
  line-height: 18px;
  color: #909399;
  white-space: nowrap;
}

.order-workflow-steps__line {
  position: absolute;
  top: 50%;
  right: 0;
  width: 10px;
  height: 1px;
  background: #dcdfe6;
  transform: translateY(-50%);
}

.order-workflow-steps__item.is-done .order-workflow-steps__dot {
  background: #67c23a;
}

.order-workflow-steps__item.is-done .order-workflow-steps__label {
  color: #606266;
}

.order-workflow-steps__item.is-current .order-workflow-steps__dot {
  background: #409eff;
  box-shadow: 0 0 0 3px rgba(64, 158, 255, 0.15);
}

.order-workflow-steps__item.is-current .order-workflow-steps__label {
  color: #303133;
  font-weight: 600;
}

.order-workflow-steps__item.is-error .order-workflow-steps__dot {
  background: #f56c6c;
}

.order-workflow-steps__item.is-error .order-workflow-steps__label {
  color: #f56c6c;
  font-weight: 600;
}
</style>
