-- Insert Vietnamese Language Type
INSERT INTO `eb_lang_type` (`language_name`, `file_name`, `is_default`, `status`, `is_del`, `create_time`, `update_time`) 
SELECT 'Vietnamese', 'vi-VN', 0, 1, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP() 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_type` WHERE `file_name` = 'vi-VN');

-- Insert Translations
-- Using a variable to store type_id
SET @vi_type_id = (SELECT `id` FROM `eb_lang_type` WHERE `file_name` = 'vi-VN' LIMIT 1);

INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '${pageType == 1 ? \'绑定手机号\' : \'立即登录\'}', '[VI] ${pageType == 1 ? \'绑定手机号\' : \'立即登录\'}', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '${pageType == 1 ? \'绑定手机号\' : \'立即登录\'}');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '100%正品保证', '[VI] 100%正品保证', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '100%正品保证');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, 'ID号', '[VI] ID号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = 'ID号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, 'SVIP会员', '[VI] SVIP会员', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = 'SVIP会员');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, 'SVIP会员尊享权', '[VI] SVIP会员尊享权', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = 'SVIP会员尊享权');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, 'SVIP商品推荐', '[VI] SVIP商品推荐', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = 'SVIP商品推荐');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '[商品]', '[VI] [商品]', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '[商品]');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '[图片]', '[VI] [图片]', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '[图片]');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '[订单]', '[VI] [订单]', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '[订单]');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '[语音]', '[VI] [语音]', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '[语音]');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '[默认]', '[VI] [默认]', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '[默认]');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '《用户协议》', '[VI] 《用户协议》', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '《用户协议》');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '《隐私协议》', '[VI] 《隐私协议》', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '《隐私协议》');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '一', 'Nhất', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '一');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '一级', '[VI] 一级', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '一级');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '一级分佣比例', '[VI] 一级分佣比例', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '一级分佣比例');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '一键改价', '[VI] 一键改价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '一键改价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '一键领取', '[VI] 一键领取', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '一键领取');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '三', 'Tam', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '三');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '上传图片', '[VI] 上传图片', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '上传图片');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '上拉加载更多', '[VI] 上拉加载更多', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '上拉加载更多');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '下单时间', '[VI] 下单时间', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '下单时间');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '下载进度', '[VI] 下载进度', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '下载进度');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '不使用', '[VI] 不使用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '不使用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '不开发票', 'Không xuất hóa đơn', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '不开发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '不支持自提', '[VI] 不支持自提', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '不支持自提');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '不支持配送', '[VI] 不支持配送', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '不支持配送');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '不能输入0喔', '[VI] 不能输入0喔', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '不能输入0喔');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '不限时', '[VI] 不限时', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '不限时');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '与', '[VI] 与', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '与');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '专属优惠券', '[VI] 专属优惠券', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '专属优惠券');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '专属徽章', '[VI] 专属徽章', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '专属徽章');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '专用', '[VI] 专用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '专用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '两次输入的密码不一致！', '[VI] 两次输入的密码不一致！', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '两次输入的密码不一致！');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '个人', 'Cá nhân', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '个人');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '个人中心', '[VI] 个人中心', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '个人中心');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '个人仅支持普通发票', '[VI] 个人仅支持普通发票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '个人仅支持普通发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '个人普通发票', '[VI] 个人普通发票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '个人普通发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '中奖记录', '[VI] 中奖记录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '中奖记录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '中评', 'Đánh giá trung bình', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '中评');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '为保障权益，请收到货确认无误后，再确认收货', '[VI] 为保障权益，请收到货确认无误后，再确认收货', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '为保障权益，请收到货确认无误后，再确认收货');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '主播', '[VI] 主播', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '主播');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '九宫格抽奖活动', '[VI] 九宫格抽奖活动', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '九宫格抽奖活动');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '买家备注', '[VI] 买家备注', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '买家备注');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '买家留言', '[VI] 买家留言', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '买家留言');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '事业部', '[VI] 事业部', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '事业部');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '二', 'Hai', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '二');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '二级', '[VI] 二级', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '二级');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '二级分佣比例', '[VI] 二级分佣比例', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '二级分佣比例');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '二维码获取失败', '[VI] 二维码获取失败', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '二维码获取失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '五', 'Năm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '五');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '产品介绍', '[VI] 产品介绍', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '产品介绍');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '产品库存不足，请选择其它', '[VI] 产品库存不足，请选择其它', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '产品库存不足，请选择其它');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '产品库存不足，请选择其它属性', '[VI] 产品库存不足，请选择其它属性', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '产品库存不足，请选择其它属性');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '亲、暂无消息记录哟！', '[VI] 亲、暂无消息记录哟！', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '亲、暂无消息记录哟！');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '人', '[VI] 人', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '人');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '人兑换', '[VI] 人兑换', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '人兑换');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '人分享', '[VI] 人分享', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '人分享');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '人参与', '[VI] 人参与', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '人参与');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '人参与拼团', '[VI] 人参与拼团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '人参与拼团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '人团', '[VI] 人团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '人团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '人成团', '[VI] 人成团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '人成团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '人拼', '[VI] 人拼', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '人拼');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '人拼团成功', '[VI] 人拼团成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '人拼团成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '人查看', '[VI] 人查看', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '人查看');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '人正在参与', '[VI] 人正在参与', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '人正在参与');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '人，拼团失败', '[VI] 人，拼团失败', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '人，拼团失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '仅支持到店', '[VI] 仅支持到店', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '仅支持到店');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '仅支持配送', '[VI] 仅支持配送', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '仅支持配送');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '仅退款', '[VI] 仅退款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '仅退款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '今天', '[VI] 今天', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '今天');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '今日已签到，明日再来吧', '[VI] 今日已签到，明日再来吧', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '今日已签到，明日再来吧');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '今日成交额', '[VI] 今日成交额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '今日成交额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '今日成长值', '[VI] 今日成长值', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '今日成长值');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '今日订单数', '[VI] 今日订单数', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '今日订单数');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '付费会员优惠', '[VI] 付费会员优惠', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '付费会员优惠');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '代付成功', '[VI] 代付成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '代付成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '代付订单创建成功，发给好友帮你付款吧~', '[VI] 代付订单创建成功，发给好友帮你付款吧~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '代付订单创建成功，发给好友帮你付款吧~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '代付金额', '[VI] 代付金额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '代付金额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '代理商', '[VI] 代理商', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '代理商');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '代理商协议', '[VI] 代理商协议', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '代理商协议');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '代理商名称', '[VI] 代理商名称', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '代理商名称');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '代理商申请', '[VI] 代理商申请', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '代理商申请');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '件', 'Kiện', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '件');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '件商品', '[VI] 件商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '件商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '件商品，实付款', '[VI] 件商品，实付款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '件商品，实付款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '件商品，应支付', '[VI] 件商品，应支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '件商品，应支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '件商品，总金额', '[VI] 件商品，总金额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '件商品，总金额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '件退款中', '[VI] 件退款中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '件退款中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '价格', 'Giá', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '价格');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '份', '[VI] 份', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '份');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '企业', 'Doanh nghiệp', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '企业');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '企业专用发票', '[VI] 企业专用发票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '企业专用发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '企业地址', '[VI] 企业地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '企业地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '企业普通发票', '[VI] 企业普通发票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '企业普通发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '企业电话', '[VI] 企业电话', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '企业电话');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '企业税号', '[VI] 企业税号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '企业税号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '优品推荐', '[VI] 优品推荐', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '优品推荐');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '优惠券', 'Phiếu giảm giá', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '优惠券');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '优惠券抵扣', 'Khấu trừ phiếu giảm giá', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '优惠券抵扣');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '会员优惠价', '[VI] 会员优惠价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '会员优惠价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '会员优惠券', '[VI] 会员优惠券', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '会员优惠券');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '会员到期', '[VI] 会员到期', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '会员到期');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '会员可享多项权益', '[VI] 会员可享多项权益', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '会员可享多项权益');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '会员用户协议', '[VI] 会员用户协议', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '会员用户协议');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '会员运费优惠', '[VI] 会员运费优惠', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '会员运费优惠');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '位好友成功砍价', '[VI] 位好友成功砍价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '位好友成功砍价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '余额', 'Số dư', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '余额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '余额不足', '[VI] 余额不足', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '余额不足');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '余额支付', 'Thanh toán bằng số dư', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '余额支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '你不是该团的成员', '[VI] 你不是该团的成员', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '你不是该团的成员');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '佣金排行', 'Xếp hạng hoa hồng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '佣金排行');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '佣金明细', '[VI] 佣金明细', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '佣金明细');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '佣金转入', '[VI] 佣金转入', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '佣金转入');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '使用微信快捷支付', '[VI] 使用微信快捷支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '使用微信快捷支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '使用支付宝支付', '[VI] 使用支付宝支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '使用支付宝支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '使用线上支付宝支付', '[VI] 使用线上支付宝支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '使用线上支付宝支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '使用线下付款', '[VI] 使用线下付款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '使用线下付款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '使用说明', 'Hướng dẫn sử dụng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '使用说明');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '使用通联支付付款', '[VI] 使用通联支付付款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '使用通联支付付款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '例如：SF000000000000:3941', '[VI] 例如：SF000000000000:3941', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '例如：SF000000000000:3941');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '促销单品', '[VI] 促销单品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '促销单品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '保存', 'Lưu', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '保存');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '保存中', '[VI] 保存中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '保存中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '保存修改', '[VI] 保存修改', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '保存修改');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '保存到手机', '[VI] 保存到手机', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '保存到手机');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '保存图片', 'Lưu ảnh', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '保存图片');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '保存失败', 'Lưu thất bại', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '保存失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '保存成功', 'Lưu thành công', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '保存成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '保存海报', '[VI] 保存海报', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '保存海报');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '修改分佣比例', '[VI] 修改分佣比例', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '修改分佣比例');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '修改地址', '[VI] 修改地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '修改地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '修改成功', '[VI] 修改成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '修改成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '倒计时', 'Đếm ngược', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '倒计时');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '元', 'Tệ', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '元');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '元.', '[VI] 元.', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '元.');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '元可用', '[VI] 元可用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '元可用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '元，听说分享次数越多砍价成功的机会越大哦', '[VI] 元，听说分享次数越多砍价成功的机会越大哦', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '元，听说分享次数越多砍价成功的机会越大哦');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '元，最高500元', '[VI] 元，最高500元', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '元，最高500元');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '充值', 'Nạp tiền', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '充值');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '充值记录', '[VI] 充值记录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '充值记录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '先领券 再购物', '[VI] 先领券 再购物', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '先领券 再购物');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '免运费', '[VI] 免运费', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '免运费');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '兑换', '[VI] 兑换', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '兑换');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '兑换成功', 'Đổi thành công', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '兑换成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '兑换方式', '[VI] 兑换方式', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '兑换方式');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '兑换时间', '[VI] 兑换时间', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '兑换时间');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '兑换记录', 'Lịch sử đổi', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '兑换记录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '全选', 'Chọn tất cả', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '全选');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '全部', 'Tất cả', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '全部');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '全部商品', '[VI] 全部商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '全部商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '全部已读', '[VI] 全部已读', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '全部已读');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '六', 'Sáu', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '六');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '共', '[VI] 共', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '共');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '关注', '[VI] 关注', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '关注');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '关注公众号', '[VI] 关注公众号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '关注公众号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '关闭', 'Đóng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '关闭');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '其他', 'Khác', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '其他');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '其他方式登录', '[VI] 其他方式登录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '其他方式登录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '再次开团', '[VI] 再次开团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '再次开团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '再次购买', 'Mua lại', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '再次购买');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '再累计签到', '[VI] 再累计签到', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '再累计签到');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '冻结佣金', '[VI] 冻结佣金', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '冻结佣金');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '冻结佣金为', '[VI] 冻结佣金为', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '冻结佣金为');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '冻结积分', '[VI] 冻结积分', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '冻结积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '减', '[VI] 减', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '减');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '分', 'Phân', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '分享失败', '[VI] 分享失败', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '分享失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '分享成功', '[VI] 分享成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '分享成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '分佣比例', '[VI] 分佣比例', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '分佣比例');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '分值提升', '[VI] 分值提升', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '分值提升');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '分值明细', '[VI] 分值明细', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '分值明细');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '分销员协议', '[VI] 分销员协议', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '分销员协议');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '分销员姓名', '[VI] 分销员姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '分销员姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '分销海报', '[VI] 分销海报', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '分销海报');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '分销等级', '[VI] 分销等级', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '分销等级');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '切换地址', '[VI] 切换地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '切换地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '切换的账号不存在', '[VI] 切换的账号不存在', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '切换的账号不存在');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '切换门店', '[VI] 切换门店', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '切换门店');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '创建订单中', '[VI] 创建订单中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '创建订单中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '删除', 'Xóa', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '删除');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '删除员工', '[VI] 删除员工', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '删除员工');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '删除成功', '[VI] 删除成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '删除成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '删除订单', 'Xóa đơn hàng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '删除订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '删除该发票？', '[VI] 删除该发票？', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '删除该发票？');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '到店自提', '[VI] 到店自提', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '到店自提');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '到手价', '[VI] 到手价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '到手价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '到期', '[VI] 到期', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '到期');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '刷新支付状态', '[VI] 刷新支付状态', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '刷新支付状态');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '券', '[VI] 券', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '券');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '剩余', '[VI] 剩余', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '剩余');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '剩余抽奖次数为0', '[VI] 剩余抽奖次数为0', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '剩余抽奖次数为0');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '剩余积分', '[VI] 剩余积分', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '剩余积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '加入时间', '[VI] 加入时间', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '加入时间');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '加入购物车', 'Thêm vào giỏ hàng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '加入购物车');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '加载中', 'Đang tải', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '加载中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '加载失败', 'Tải thất bại', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '加载失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '加载更多', 'Tải thêm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '加载更多');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '区', '[VI] 区', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '区');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '千米距离', '[VI] 千米距离', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '千米距离');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '单', 'Đơn', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '单位', '[VI] 单位', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '单位');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '单号', '[VI] 单号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '单号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '单次限购', '[VI] 单次限购', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '单次限购');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '单独购买', '[VI] 单独购买', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '单独购买');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '卡号', '[VI] 卡号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '卡号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '原因', '[VI] 原因', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '原因');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '原始邮费', '[VI] 原始邮费', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '原始邮费');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '去付款', '[VI] 去付款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '去付款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '去发货', '[VI] 去发货', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '去发货');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '去完成', '[VI] 去完成', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '去完成');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '去抢购', '[VI] 去抢购', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '去抢购');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '去拼单', 'Đi ghép đơn', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '去拼单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '去拼团', '[VI] 去拼团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '去拼团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '去看看', '[VI] 去看看', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '去看看');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '去签到', '[VI] 去签到', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '去签到');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '去购买', '[VI] 去购买', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '去购买');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '去逛逛', '[VI] 去逛逛', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '去逛逛');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '去邀请', '[VI] 去邀请', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '去邀请');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '去领取', '[VI] 去领取', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '去领取');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '参与拼团', 'Tham gia nhóm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '参与拼团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '参与砍价', '[VI] 参与砍价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '参与砍价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '参与秒杀', '[VI] 参与秒杀', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '参与秒杀');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '参数', '[VI] 参数', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '参数');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '参数错误', '[VI] 参数错误', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '参数错误');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发现新版本', '[VI] 发现新版本', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发现新版本');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发票', 'Hóa đơn', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发票信息', '[VI] 发票信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发票信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发票备注', '[VI] 发票备注', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发票备注');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发票抬头', '[VI] 发票抬头', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发票抬头');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发票抬头类型', '[VI] 发票抬头类型', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发票抬头类型');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发票管理', '[VI] 发票管理', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发票管理');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发票类型', '[VI] 发票类型', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发票类型');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发票类型选择', '[VI] 发票类型选择', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发票类型选择');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发票编号', '[VI] 发票编号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发票编号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发票记录', '[VI] 发票记录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发票记录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发货', '[VI] 发货', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发货');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发货方式', '[VI] 发货方式', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发货方式');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发货类型', '[VI] 发货类型', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发货类型');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发送客服', '[VI] 发送客服', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发送客服');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发送给微信好友', '[VI] 发送给微信好友', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发送给微信好友');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '发送给朋友', '[VI] 发送给朋友', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '发送给朋友');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '取关', '[VI] 取关', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '取关');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '取消', 'Hủy bỏ', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '取消');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '取消兑换', '[VI] 取消兑换', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '取消兑换');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '取消开团', '[VI] 取消开团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '取消开团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '取消支付', '[VI] 取消支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '取消支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '取消活动', '[VI] 取消活动', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '取消活动');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '取消申请', '[VI] 取消申请', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '取消申请');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '取消订单', 'Hủy đơn hàng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '取消订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '取消选择', '[VI] 取消选择', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '取消选择');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '可将二维码出示给店员扫描或提供数字核销码', '[VI] 可将二维码出示给店员扫描或提供数字核销码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '可将二维码出示给店员扫描或提供数字核销码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '可将二维码出示给配送员进行核销', '[VI] 可将二维码出示给配送员进行核销', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '可将二维码出示给配送员进行核销');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '可用', '[VI] 可用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '可用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '可用余额', '[VI] 可用余额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '可用余额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '可用余额:', '[VI] 可用余额:', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '可用余额:');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '可用积分', '[VI] 可用积分', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '可用积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '可用积分不足！', '[VI] 可用积分不足！', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '可用积分不足！');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '可获得', '[VI] 可获得', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '可获得');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '合计', '[VI] 合计', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '合计');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '同意并继续', '[VI] 同意并继续', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '同意并继续');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '同意退货', '[VI] 同意退货', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '同意退货');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '员工人数', '[VI] 员工人数', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '员工人数');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '员工列表', '[VI] 员工列表', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '员工列表');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '周', 'Tuần', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '周');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '周排行', '[VI] 周排行', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '周排行');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '周榜', '[VI] 周榜', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '周榜');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '呼朋唤友来砍价', '[VI] 呼朋唤友来砍价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '呼朋唤友来砍价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '和好友一起分享', '[VI] 和好友一起分享', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '和好友一起分享');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '品牌券', '[VI] 品牌券', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '品牌券');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '品类券', '[VI] 品类券', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '品类券');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '售价', '[VI] 售价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '售价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '售后无忧', '[VI] 售后无忧', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '售后无忧');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商品', 'Sản phẩm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商品兑换成功', '[VI] 商品兑换成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商品兑换成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商品券', '[VI] 商品券', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商品券');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商品总价', '[VI] 商品总价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商品总价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商品暂无库存', '[VI] 商品暂无库存', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商品暂无库存');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商品满足你的期待么？说说你的想法，分享给想买的他们吧', '[VI] 商品满足你的期待么？说说你的想法，分享给想买的他们吧', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商品满足你的期待么？说说你的想法，分享给想买的他们吧');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商品详情', '[VI] 商品详情', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商品详情');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商品质量', '[VI] 商品质量', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商品质量');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商城价', '[VI] 商城价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商城价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商城客服已离线', '[VI] 商城客服已离线', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商城客服已离线');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商城登录', '[VI] 商城登录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商城登录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商城的第', '[VI] 商城的第', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商城的第');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商城购物可享', '[VI] 商城购物可享', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商城购物可享');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商家备注', '[VI] 商家备注', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商家备注');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商家暂未上架活动哦', '[VI] 商家暂未上架活动哦', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商家暂未上架活动哦');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '商家管理', '[VI] 商家管理', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '商家管理');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '四', 'Bốn', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '四');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '回到当天', '[VI] 回到当天', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '回到当天');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '回放', '[VI] 回放', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '回放');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '团长', '[VI] 团长', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '团长');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '团队排序', '[VI] 团队排序', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '团队排序');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '固定', '[VI] 固定', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '固定');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '图片加载中', '[VI] 图片加载中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '图片加载中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '图片最多可上传10张,图片格式支持JPG、PNG、JPEG', '[VI] 图片最多可上传10张,图片格式支持JPG、PNG、JPEG', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '图片最多可上传10张,图片格式支持JPG、PNG、JPEG');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '在设置中是否已开启网络权限', '[VI] 在设置中是否已开启网络权限', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '在设置中是否已开启网络权限');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '地址信息', '[VI] 地址信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '地址信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '地址管理', '[VI] 地址管理', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '地址管理');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '填写备注信息，100字以内', '[VI] 填写备注信息，100字以内', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '填写备注信息，100字以内');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '填写好友留言，最多40字', '[VI] 填写好友留言，最多40字', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '填写好友留言，最多40字');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '填写寄件人地址', '[VI] 填写寄件人地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '填写寄件人地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '填写寄件人姓名', '[VI] 填写寄件人姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '填写寄件人姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '填写寄件人电话', '[VI] 填写寄件人电话', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '填写寄件人电话');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '填写快递单号', '[VI] 填写快递单号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '填写快递单号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '填写您的新密码', '[VI] 填写您的新密码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '填写您的新密码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '填写手机号码', '[VI] 填写手机号码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '填写手机号码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '填写登录密码', '[VI] 填写登录密码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '填写登录密码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '填写退货信息', '[VI] 填写退货信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '填写退货信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '填写验证码', '[VI] 填写验证码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '填写验证码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '增值税电子专用发票', '[VI] 增值税电子专用发票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '增值税电子专用发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '增值税电子普通发票', '[VI] 增值税电子普通发票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '增值税电子普通发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '增长', '[VI] 增长', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '增长');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '增长率', '[VI] 增长率', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '增长率');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '备注', 'Ghi chú', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '备注');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '备注信息', '[VI] 备注信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '备注信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '备注说明', '[VI] 备注说明', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '备注说明');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '复制', 'Sao chép', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '复制');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '复制单号', '[VI] 复制单号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '复制单号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '复制口令', '[VI] 复制口令', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '复制口令');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '复制失败', 'Sao chép thất bại', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '复制失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '复制成功', 'Sao chép thành công', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '复制成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '复制礼物链接', '[VI] 复制礼物链接', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '复制礼物链接');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '大家都在拼', '[VI] 大家都在拼', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '大家都在拼');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '大家都在换', '[VI] 大家都在换', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '大家都在换');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '天', 'Ngày', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '天');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '天.', '[VI] 天.', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '天.');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '天内发货', '[VI] 天内发货', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '天内发货');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '天内可用', '[VI] 天内可用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '天内可用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '天，到期后可提现', '[VI] 天，到期后可提现', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '天，到期后可提现');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '天，可额外获得惊喜礼包', '[VI] 天，可额外获得惊喜礼包', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '天，可额外获得惊喜礼包');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '失效', '[VI] 失效', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '失效');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '失效商品', '[VI] 失效商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '失效商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '失败原因', '[VI] 失败原因', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '失败原因');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '头像', '[VI] 头像', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '头像');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '奖品名称', '[VI] 奖品名称', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '奖品名称');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '奖品类型', '[VI] 奖品类型', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '奖品类型');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '好友代付', 'Bạn bè thanh toán hộ', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '好友代付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '好友代付成功，商家正在努力发货中~', '[VI] 好友代付成功，商家正在努力发货中~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '好友代付成功，商家正在努力发货中~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '好友已砍价成功', '[VI] 好友已砍价成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '好友已砍价成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '好友留言', '[VI] 好友留言', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '好友留言');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '好的', '[VI] 好的', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '好的');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '好评', 'Đánh giá tốt', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '好评');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '好评榜', '[VI] 好评榜', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '好评榜');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '好评率', '[VI] 好评率', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '好评率');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '如果订单申请退款，已支付金额将原路退还给您', '[VI] 如果订单申请退款，已支付金额将原路退还给您', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '如果订单申请退款，已支付金额将原路退还给您');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '姓名', '[VI] 姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '完成支付', '[VI] 完成支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '完成支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '实付款', 'Thực trả', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '实付款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '实际到账:', '[VI] 实际到账:', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '实际到账:');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '实际支付', '[VI] 实际支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '实际支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '审核未通过', '[VI] 审核未通过', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '审核未通过');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '审核通过', 'Đã duyệt', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '审核通过');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '客服已下线，是否需要反馈？', '[VI] 客服已下线，是否需要反馈？', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '客服已下线，是否需要反馈？');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '客服连接中', '[VI] 客服连接中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '客服连接中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '寄件人地址', '[VI] 寄件人地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '寄件人地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '寄件人姓名', '[VI] 寄件人姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '寄件人姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '寄件人电话', '[VI] 寄件人电话', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '寄件人电话');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '密码', '[VI] 密码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '密码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '导入微信地址', '[VI] 导入微信地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '导入微信地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '尊享客服', '[VI] 尊享客服', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '尊享客服');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '小程序二维码需要发布正式版后才能获取到', '[VI] 小程序二维码需要发布正式版后才能获取到', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '小程序二维码需要发布正式版后才能获取到');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '属性', '[VI] 属性', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '属性');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '差评', 'Đánh giá kém', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '差评');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已下架', '[VI] 已下架', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已下架');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已使用', 'Đã sử dụng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已使用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已使用/过期', '[VI] 已使用/过期', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已使用/过期');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已兑换', '[VI] 已兑换', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已兑换');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已发货，请注意查收', '[VI] 已发货，请注意查收', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已发货，请注意查收');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已取消', '[VI] 已取消', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已取消');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已取消！', '[VI] 已取消！', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已取消！');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已售', '[VI] 已售', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已售');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已售罄', '[VI] 已售罄', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已售罄');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已失效', '[VI] 已失效', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已失效');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已完成', 'Đã hoàn thành', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已完成');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已开票', '[VI] 已开票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已开票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已成功帮助好友砍价', '[VI] 已成功帮助好友砍价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已成功帮助好友砍价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已抢', 'Đã mua', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已抢');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已拼', '[VI] 已拼', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已拼');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已支付', '[VI] 已支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已有', '[VI] 已有', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已有');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已砍', '[VI] 已砍', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已砍');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已砍至', '[VI] 已砍至', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已砍至');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已累积为您节省', '[VI] 已累积为您节省', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已累积为您节省');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已累计签到', '[VI] 已累计签到', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已累计签到');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已经有人替我代付，谢谢啦~', '[VI] 已经有人替我代付，谢谢啦~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已经有人替我代付，谢谢啦~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已结束', 'Đã kết thúc', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已结束');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已解锁更高等级', '[VI] 已解锁更高等级', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已解锁更高等级');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已评价', '[VI] 已评价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已评价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已过期', 'Đã hết hạn', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已过期');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已退款', '[VI] 已退款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已退款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已选商品', '[VI] 已选商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已选商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已选择', '[VI] 已选择', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已选择');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已阅读并同意', '[VI] 已阅读并同意', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已阅读并同意');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已预定', '[VI] 已预定', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已预定');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已预订', '[VI] 已预订', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已预订');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已领取', 'Đã nhận', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已领取');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '已领完', '[VI] 已领完', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '已领完');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '市', '[VI] 市', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '市');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '帮好友砍一刀', '[VI] 帮好友砍一刀', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '帮好友砍一刀');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '帮我付一下这件商品了，谢谢~', '[VI] 帮我付一下这件商品了，谢谢~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '帮我付一下这件商品了，谢谢~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '年', 'Năm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '年');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '幸运抽奖可获得积分奖励', '[VI] 幸运抽奖可获得积分奖励', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '幸运抽奖可获得积分奖励');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '广东省', '[VI] 广东省', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '广东省');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '广州市', '[VI] 广州市', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '广州市');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '序号', '[VI] 序号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '序号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '库存', 'Kho', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '库存');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '店小二', '[VI] 店小二', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '店小二');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '开具发票', '[VI] 开具发票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '开具发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '开售时间', '[VI] 开售时间', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '开售时间');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '开团/参团', '[VI] 开团/参团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '开团/参团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '开户银行', '[VI] 开户银行', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '开户银行');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '开通“超级会员”立省', '[VI] 开通“超级会员”立省', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '开通“超级会员”立省');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '开通会员', 'Mở thành viên', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '开通会员');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '开通即享会员权益', '[VI] 开通即享会员权益', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '开通即享会员权益');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当前', '[VI] 当前', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当前');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当前为最新版本', '[VI] 当前为最新版本', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当前为最新版本');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当前共', '[VI] 当前共', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当前共');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当前可提现金额', '[VI] 当前可提现金额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当前可提现金额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当前可用余额：', '[VI] 当前可用余额：', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当前可用余额：');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当前可转入佣金为', '[VI] 当前可转入佣金为', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当前可转入佣金为');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当前手机号', '[VI] 当前手机号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当前手机号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当前是否处于弱网环境', '[VI] 当前是否处于弱网环境', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当前是否处于弱网环境');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当前版本', 'Phiên bản hiện tại', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当前版本');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当前积分', '[VI] 当前积分', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当前积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当前等级', '[VI] 当前等级', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当前等级');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当前限时秒杀', '[VI] 当前限时秒杀', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当前限时秒杀');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '当您点击同意并开始时用产品服务时，即表示您已理解并同息该条款内容，该条款将对您产生法律约束力。如您拒绝，将无法继续下一步操作。', '[VI] 当您点击同意并开始时用产品服务时，即表示您已理解并同息该条款内容，该条款将对您产生法律约束力。如您拒绝，将无法继续下一步操作。', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '当您点击同意并开始时用产品服务时，即表示您已理解并同息该条款内容，该条款将对您产生法律约束力。如您拒绝，将无法继续下一步操作。');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '待付款', 'Chờ thanh toán', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '待付款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '待发货', 'Chờ giao hàng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '待发货');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '待审核', '[VI] 待审核', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '待审核');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '待收货', 'Chờ nhận hàng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '待收货');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '待核销', '[VI] 待核销', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '待核销');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '待用户发货', '[VI] 待用户发货', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '待用户发货');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '待评价', 'Chờ đánh giá', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '待评价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '待领取', '[VI] 待领取', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '待领取');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '微信', '[VI] 微信', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '微信');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '微信好友', '[VI] 微信好友', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '微信好友');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '微信支付', 'Thanh toán WeChat', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '微信支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '微信朋友圈', '[VI] 微信朋友圈', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '微信朋友圈');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '微信登录', '[VI] 微信登录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '微信登录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '快递', 'Chuyển phát nhanh', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '快递');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '快递公司', '[VI] 快递公司', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '快递公司');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '快递单号', '[VI] 快递单号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '快递单号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '快递号', '[VI] 快递号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '快递号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '快递费用', '[VI] 快递费用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '快递费用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '快递配送', '[VI] 快递配送', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '快递配送');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '快速升级技巧', '[VI] 快速升级技巧', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '快速升级技巧');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '快速登录', '[VI] 快速登录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '快速登录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '总代付', '[VI] 总代付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '总代付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '总消费', '[VI] 总消费', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '总消费');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '总资产(元)', '[VI] 总资产(元)', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '总资产(元)');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '恭喜您', '[VI] 恭喜您', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '恭喜您');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '恭喜您拼团成功', '[VI] 恭喜您拼团成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '恭喜您拼团成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '恭喜您砍价成功，快去支付', '[VI] 恭喜您砍价成功，快去支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '恭喜您砍价成功，快去支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '恭喜，您的资料提交成功！', '[VI] 恭喜，您的资料提交成功！', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '恭喜，您的资料提交成功！');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '恭喜，您的资料通过审核！', '[VI] 恭喜，您的资料通过审核！', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '恭喜，您的资料通过审核！');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您与', '[VI] 您与', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您与');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您也可以砍价低价拿哦，快去挑选心仪的商品吧', '[VI] 您也可以砍价低价拿哦，快去挑选心仪的商品吧', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您也可以砍价低价拿哦，快去挑选心仪的商品吧');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您今日已签到!', '[VI] 您今日已签到!', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您今日已签到!');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您删除的地址不存在!', '[VI] 您删除的地址不存在!', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您删除的地址不存在!');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您已取消绑定！', '[VI] 您已取消绑定！', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您已取消绑定！');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您已拒绝导入微信地址权限', '[VI] 您已拒绝导入微信地址权限', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您已拒绝导入微信地址权限');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您已砍掉', '[VI] 您已砍掉', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您已砍掉');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您所在的企业地址', '[VI] 您所在的企业地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您所在的企业地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您的企业电话', '[VI] 您的企业电话', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您的企业电话');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您的好友', '[VI] 您的好友', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您的好友');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您的开户银行', '[VI] 您的开户银行', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您的开户银行');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您的手机号', '[VI] 您的手机号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您的手机号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您的申请未通过！', '[VI] 您的申请未通过！', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您的申请未通过！');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您的联系邮箱', '[VI] 您的联系邮箱', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您的联系邮箱');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您的银行账号', '[VI] 您的银行账号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您的银行账号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您目前暂无排名', '[VI] 您目前暂无排名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您目前暂无排名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您目前暂无推广权限', '[VI] 您目前暂无推广权限', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您目前暂无推广权限');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您目前的排名', '[VI] 您目前的排名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您目前的排名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您确认放弃此次申请吗', '[VI] 您确认放弃此次申请吗', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您确认放弃此次申请吗');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您设置的默认地址不存在!', '[VI] 您设置的默认地址不存在!', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您设置的默认地址不存在!');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您输入的密码过于简单', '[VI] 您输入的密码过于简单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您输入的密码过于简单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '您还没有添加发票信息哟', '[VI] 您还没有添加发票信息哟', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '您还没有添加发票信息哟');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '感谢您的评价', '[VI] 感谢您的评价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '感谢您的评价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '成交额', '[VI] 成交额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '成交额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '成功帮砍', '[VI] 成功帮砍', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '成功帮砍');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '成功开启试用', '[VI] 成功开启试用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '成功开启试用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我也是有底线的', '[VI] 我也是有底线的', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我也是有底线的');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我也要参与', '[VI] 我也要参与', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我也要参与');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我已为你代付成功，商家正在努力发货中~', '[VI] 我已为你代付成功，商家正在努力发货中~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我已为你代付成功，商家正在努力发货中~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我的余额', '[VI] 我的余额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我的余额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我的奖品', '[VI] 我的奖品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我的奖品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我的成长值记录', '[VI] 我的成长值记录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我的成长值记录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我的成长特权', '[VI] 我的成长特权', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我的成长特权');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我的收藏', '[VI] 我的收藏', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我的收藏');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我的服务', '[VI] 我的服务', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我的服务');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我的积分', 'Điểm của tôi', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我的积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我知道了', '[VI] 我知道了', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我知道了');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我要参团', '[VI] 我要参团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我要参团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '我要反馈', '[VI] 我要反馈', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '我要反馈');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '所在地区', '[VI] 所在地区', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '所在地区');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '所有商品精挑细选', '[VI] 所有商品精挑细选', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '所有商品精挑细选');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '手动填写', '[VI] 手动填写', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '手动填写');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '手机号', 'Số điện thoại', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '手机号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '手机号登录', 'Đăng nhập bằng số điện thoại', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '手机号登录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '手机号码', '[VI] 手机号码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '手机号码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '手机号码不存在,无法发送验证码！', '[VI] 手机号码不存在,无法发送验证码！', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '手机号码不存在,无法发送验证码！');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '批量退款', '[VI] 批量退款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '批量退款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '找回密码', 'Lấy lại mật khẩu', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '找回密码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '找微信好友支付', '[VI] 找微信好友支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '找微信好友支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '折', '[VI] 折', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '折');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '抢', '[VI] 抢', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '抢');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '抢更多商品', '[VI] 抢更多商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '抢更多商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '抢购中', 'Đang mua', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '抢购中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '抢购详情页', '[VI] 抢购详情页', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '抢购详情页');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '抬头管理', '[VI] 抬头管理', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '抬头管理');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '抬头类型', '[VI] 抬头类型', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '抬头类型');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '抬头选择', '[VI] 抬头选择', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '抬头选择');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '抽奖', '[VI] 抽奖', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '抽奖');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '拒绝原因', '[VI] 拒绝原因', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '拒绝原因');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '拒绝退款', '[VI] 拒绝退款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '拒绝退款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '拨打', '[VI] 拨打', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '拨打');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '拼团', 'Nhóm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '拼团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '拼团中，还差', '[VI] 拼团中，还差', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '拼团中，还差');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '拼团列表', 'Danh sách nhóm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '拼团列表');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '拼团海报', '[VI] 拼团海报', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '拼团海报');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '拼团玩法', '[VI] 拼团玩法', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '拼团玩法');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '拼团简介', '[VI] 拼团简介', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '拼团简介');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '持卡人', '[VI] 持卡人', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '持卡人');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '指定范围', '[VI] 指定范围', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '指定范围');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '授权登录', '[VI] 授权登录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '授权登录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '推广享佣金', '[VI] 推广享佣金', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '推广享佣金');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '推广人排行', '[VI] 推广人排行', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '推广人排行');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '推广人数', '[VI] 推广人数', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '推广人数');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '推广人统计', '[VI] 推广人统计', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '推广人统计');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '推广人订单', '[VI] 推广人订单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '推广人订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '推广名片', '[VI] 推广名片', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '推广名片');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '推广订单', '[VI] 推广订单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '推广订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '推广订单列表', '[VI] 推广订单列表', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '推广订单列表');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '推荐', 'Đề xuất', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '推荐');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提交', 'Gửi', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提交');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提交时间', '[VI] 提交时间', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提交时间');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提交申请', '[VI] 提交申请', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提交申请');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提交订单', '[VI] 提交订单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提交订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提供具有辨识度的用户中心界面', '[VI] 提供具有辨识度的用户中心界面', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提供具有辨识度的用户中心界面');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提现', 'Rút tiền', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提现');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提现到余额', '[VI] 提现到余额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提现到余额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提现到余额后无法再次转出，确认是否提现到余额', '[VI] 提现到余额后无法再次转出，确认是否提现到余额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提现到余额后无法再次转出，确认是否提现到余额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提现到余额成功', '[VI] 提现到余额成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提现到余额成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提现手续费:', '[VI] 提现手续费:', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提现手续费:');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提现方式', 'Phương thức rút tiền', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提现方式');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提现最低', '[VI] 提现最低', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提现最低');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提现金额不能低于', '[VI] 提现金额不能低于', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提现金额不能低于');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提现金额不能高于500', '[VI] 提现金额不能高于500', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提现金额不能高于500');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提示', 'Gợi ý', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提示');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提示：你有', '[VI] 提示：你有', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提示：你有');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提示：点击图片即可保存至手机相册', '[VI] 提示：点击图片即可保存至手机相册', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提示：点击图片即可保存至手机相册');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '提示：积分数值的高低会直接影响您的会员等级', '[VI] 提示：积分数值的高低会直接影响您的会员等级', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '提示：积分数值的高低会直接影响您的会员等级');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '搜索', 'Tìm kiếm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '搜索');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '搜索历史', '[VI] 搜索历史', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '搜索历史');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '搜索商品名称', '[VI] 搜索商品名称', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '搜索商品名称');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '搜索用户名/订单号/电话', '[VI] 搜索用户名/订单号/电话', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '搜索用户名/订单号/电话');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '操作成功', 'Thao tác thành công', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '操作成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付', 'Thanh toán', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付中', '[VI] 支付中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付剩余时间', '[VI] 支付剩余时间', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付剩余时间');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付失败', '[VI] 支付失败', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付失败：${extraData.errmsg}', '[VI] 支付失败：${extraData.errmsg}', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付失败：${extraData.errmsg}');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付宝', '[VI] 支付宝', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付宝');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付宝支付', 'Thanh toán Alipay', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付宝支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付已取消', '[VI] 支付已取消', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付已取消');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付成功', '[VI] 支付成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付提醒', '[VI] 支付提醒', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付提醒');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付方式', '[VI] 支付方式', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付方式');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付状态', '[VI] 支付状态', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付状态');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付积分', '[VI] 支付积分', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付订单不存在,页面将在2秒后自动关闭', '[VI] 支付订单不存在,页面将在2秒后自动关闭', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付订单不存在,页面将在2秒后自动关闭');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '支付金额', '[VI] 支付金额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '支付金额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '收下礼物', '[VI] 收下礼物', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '收下礼物');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '收款码', '[VI] 收款码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '收款码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '收藏', 'Yêu thích', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '收藏');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '收藏榜', '[VI] 收藏榜', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '收藏榜');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '收货人', 'Người nhận', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '收货人');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '收货地址', 'Địa chỉ nhận hàng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '收货地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '收起', 'Thu gọn', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '收起');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '改价失败', '[VI] 改价失败', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '改价失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '改价成功', '[VI] 改价成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '改价成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '数据统计', '[VI] 数据统计', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '数据统计');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '数量', 'Số lượng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '数量');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '新人专享优惠券', '[VI] 新人专享优惠券', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '新人专享优惠券');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '新人专享福利', '[VI] 新人专享福利', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '新人专享福利');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '新人商品专区', '[VI] 新人商品专区', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '新人商品专区');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '新人红包', '[VI] 新人红包', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '新人红包');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '新品', 'Sản phẩm mới', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '新品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '新用户注册即可', '[VI] 新用户注册即可', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '新用户注册即可');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '新用户注册后即可获得积分', '[VI] 新用户注册后即可获得积分', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '新用户注册后即可获得积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '新用户注册领积分', '[VI] 新用户注册领积分', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '新用户注册领积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '无法兑换', '[VI] 无法兑换', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '无法兑换');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '无门槛券', '[VI] 无门槛券', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '无门槛券');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '无需物流', '[VI] 无需物流', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '无需物流');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '日期', '[VI] 日期', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '日期');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '时', 'Giờ', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '时');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '明细', '[VI] 明细', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '明细');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '星', '[VI] 星', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '星');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '昨天', '[VI] 昨天', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '昨天');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '昨日成交额', '[VI] 昨日成交额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '昨日成交额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '昨日收益', '[VI] 昨日收益', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '昨日收益');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '昨日订单数', '[VI] 昨日订单数', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '昨日订单数');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '是否确认注销', '[VI] 是否确认注销', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '是否确认注销');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '是否绑定账号', '[VI] 是否绑定账号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '是否绑定账号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '是否进入权限管理，调整授权？', '[VI] 是否进入权限管理，调整授权？', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '是否进入权限管理，调整授权？');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '昵称', '[VI] 昵称', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '昵称');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '普通', '[VI] 普通', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '普通');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂不支付', '[VI] 暂不支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂不支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无中奖记录', '[VI] 暂无中奖记录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无中奖记录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无产品', '[VI] 暂无产品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无产品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无兑换记录～', '[VI] 暂无兑换记录～', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无兑换记录～');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无商品', '[VI] 暂无商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无商品，去看点别的吧', '[VI] 暂无商品，去看点别的吧', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无商品，去看点别的吧');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无排名~', '[VI] 暂无排名~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无排名~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无推广订单～', '[VI] 暂无推广订单～', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无推广订单～');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无数据', 'Không có dữ liệu', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无数据');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无数据~', '[VI] 暂无数据~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无数据~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无申请记录，快去申请吧!', '[VI] 暂无申请记录，快去申请吧!', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无申请记录，快去申请吧!');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无砍价记录', '[VI] 暂无砍价记录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无砍价记录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无积分记录哦～', '[VI] 暂无积分记录哦～', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无积分记录哦～');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无签到记录~', '[VI] 暂无签到记录~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无签到记录~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无经验记录', '[VI] 暂无经验记录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无经验记录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无订单', '[VI] 暂无订单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无记录', '[VI] 暂无记录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无记录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无评论', '[VI] 暂无评论', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无评论');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无账单的记录哦～', '[VI] 暂无账单的记录哦～', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无账单的记录哦～');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无退款订单~', '[VI] 暂无退款订单~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无退款订单~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无门店,请选择其他方式', '[VI] 暂无门店,请选择其他方式', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无门店,请选择其他方式');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂无门店信息', '[VI] 暂无门店信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂无门店信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂未开启等级', '[VI] 暂未开启等级', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂未开启等级');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂未支付', '[VI] 暂未支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂未支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂未解锁该等级', '[VI] 暂未解锁该等级', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂未解锁该等级');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '暂未返佣', '[VI] 暂未返佣', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '暂未返佣');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '更多', '[VI] 更多', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '更多');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '更多优惠', '[VI] 更多优惠', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '更多优惠');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '更多拼团', '[VI] 更多拼团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '更多拼团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '更换手机号码', '[VI] 更换手机号码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '更换手机号码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '最低', 'Thấp nhất', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '最低');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '最低提现金额', '[VI] 最低提现金额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '最低提现金额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '最多可上传3张', '[VI] 最多可上传3张', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '最多可上传3张');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '最多可兑换', '[VI] 最多可兑换', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '最多可兑换');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '最大限购数量${list[index].productInfo.limit_num}', '[VI] 最大限购数量${list[index].productInfo.limit_num}', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '最大限购数量${list[index].productInfo.limit_num}');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '最少添加 1 件商品', '[VI] 最少添加 1 件商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '最少添加 1 件商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '最新商品秒杀进行中', '[VI] 最新商品秒杀进行中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '最新商品秒杀进行中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '最新拼团活动', '[VI] 最新拼团活动', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '最新拼团活动');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '最新的优惠商品上架拼团', '[VI] 最新的优惠商品上架拼团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '最新的优惠商品上架拼团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '最近7天', '[VI] 最近7天', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '最近7天');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '最高返佣', '[VI] 最高返佣', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '最高返佣');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '月', 'Tháng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '月');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '月排行', '[VI] 月排行', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '月排行');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '月榜', '[VI] 月榜', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '月榜');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '有效期', 'Thời hạn hiệu lực', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '有效期');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '有效期至', '[VI] 有效期至', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '有效期至');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '服务', '[VI] 服务', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '服务');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '服务与隐私协议', '[VI] 服务与隐私协议', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '服务与隐私协议');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '服务保障', '[VI] 服务保障', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '服务保障');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '服务态度', '[VI] 服务态度', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '服务态度');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '未使用', '[VI] 未使用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '未使用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '未完成', '[VI] 未完成', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '未完成');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '未开始', '[VI] 未开始', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '未开始');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '未开票', '[VI] 未开票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '未开票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '未开通会员', '[VI] 未开通会员', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '未开通会员');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '未支付', '[VI] 未支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '未支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '未核销', '[VI] 未核销', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '未核销');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '未达成', '[VI] 未达成', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '未达成');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '本月', '[VI] 本月', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '本月');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '本月成交额', '[VI] 本月成交额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '本月成交额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '本月累计推广订单', '[VI] 本月累计推广订单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '本月累计推广订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '本月订单数', '[VI] 本月订单数', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '本月订单数');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '机会', '[VI] 机会', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '机会');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '权限设置', '[VI] 权限设置', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '权限设置');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '杭州市', '[VI] 杭州市', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '杭州市');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看', '[VI] 查看', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看会员权益', '[VI] 查看会员权益', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看会员权益');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看位置', '[VI] 查看位置', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看位置');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看全部', '[VI] 查看全部', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看全部');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看发票', '[VI] 查看发票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看商品', '[VI] 查看商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看地图', '[VI] 查看地图', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看地图');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看拼团', '[VI] 查看拼团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看拼团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看更多', 'Xem thêm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看更多');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看物流', 'Xem logistics', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看物流');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看礼物', '[VI] 查看礼物', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看礼物');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看礼物详情', '[VI] 查看礼物详情', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看礼物详情');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看订单', '[VI] 查看订单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看订单信息', '[VI] 查看订单信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看订单信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看订单详情', '[VI] 查看订单详情', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看订单详情');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看详情', '[VI] 查看详情', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看详情');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查看退货物流', '[VI] 查看退货物流', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查看退货物流');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '查询中', '[VI] 查询中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '查询中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '核销信息', '[VI] 核销信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '核销信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '次', '[VI] 次', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '次');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '欢迎您使用${mpData.siteName}！请仔细阅读以下内容，并作出适当的选择：', '[VI] 欢迎您使用${mpData.siteName}！请仔细阅读以下内容，并作出适当的选择：', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '欢迎您使用${mpData.siteName}！请仔细阅读以下内容，并作出适当的选择：');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '正在下载海报,请稍后再试', '[VI] 正在下载海报,请稍后再试', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '正在下载海报,请稍后再试');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '正在切换中', '[VI] 正在切换中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '正在切换中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '正在加载', '[VI] 正在加载', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '正在加载');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '正在加载…', '[VI] 正在加载…', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '正在加载…');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '正在加载中', '[VI] 正在加载中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '正在加载中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '正在发布评论', '[VI] 正在发布评论', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '正在发布评论');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '正在搜索中', '[VI] 正在搜索中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '正在搜索中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '正在支付', '[VI] 正在支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '正在支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '正在支付中', '[VI] 正在支付中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '正在支付中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '正在激活', '[VI] 正在激活', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '正在激活');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '正在登录中', '[VI] 正在登录中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '正在登录中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '正在确认', '[VI] 正在确认', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '正在确认');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '每日', '[VI] 每日', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '每日');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '每日签到', '[VI] 每日签到', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '每日签到');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '每日签到可获得积分奖励', '[VI] 每日签到可获得积分奖励', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '每日签到可获得积分奖励');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '每日签到可获得经验值，已签到', '[VI] 每日签到可获得经验值，已签到', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '每日签到可获得经验值，已签到');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '每日签到活动', '[VI] 每日签到活动', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '每日签到活动');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '永久', '[VI] 永久', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '永久');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '永久SVIP会员', '[VI] 永久SVIP会员', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '永久SVIP会员');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '永久限购', '[VI] 永久限购', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '永久限购');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '没有发票信息哟~', '[VI] 没有发票信息哟~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '没有发票信息哟~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '没有更多内容啦~', '[VI] 没有更多内容啦~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '没有更多内容啦~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '没有更多啦', '[VI] 没有更多啦', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '没有更多啦');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '没有登录的code，请重新扫码', '[VI] 没有登录的code，请重新扫码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '没有登录的code，请重新扫码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '注意事项', '[VI] 注意事项', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '注意事项');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '注销', '[VI] 注销', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '注销');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '注销后无法恢复', '[VI] 注销后无法恢复', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '注销后无法恢复');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '注销后无法恢复，请谨慎操作', '[VI] 注销后无法恢复，请谨慎操作', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '注销后无法恢复，请谨慎操作');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '活动', 'Hoạt động', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '活动');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '活动已结束', '[VI] 活动已结束', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '活动已结束');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '活动福利，第一时间了解', '[VI] 活动福利，第一时间了解', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '活动福利，第一时间了解');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '活动规则', '[VI] 活动规则', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '活动规则');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '活动进行中', '[VI] 活动进行中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '活动进行中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '浏览', '[VI] 浏览', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '浏览');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '浙江省', '[VI] 浙江省', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '浙江省');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '海报', 'Áp phích', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '海报');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '海报二维码生成失败', '[VI] 海报二维码生成失败', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '海报二维码生成失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '海报图片获取失败', '[VI] 海报图片获取失败', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '海报图片获取失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '海报生成中', '[VI] 海报生成中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '海报生成中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '消费', '[VI] 消费', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '消费');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '消费订单', '[VI] 消费订单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '消费订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '消费记录', '[VI] 消费记录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '消费记录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '消费金额', '[VI] 消费金额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '消费金额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '添加地址', 'Thêm địa chỉ', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '添加地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '添加失败', '[VI] 添加失败', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '添加失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '添加成功', '[VI] 添加成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '添加成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '添加新发票', '[VI] 添加新发票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '添加新发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '添加新地址', '[VI] 添加新地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '添加新地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '添加新的抬头', '[VI] 添加新的抬头', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '添加新的抬头');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '添加购物车成功', '[VI] 添加购物车成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '添加购物车成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '清空', 'Làm sạch', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '清空');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '清除成功', '[VI] 清除成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '清除成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '清除缓存', 'Xóa bộ nhớ đệm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '清除缓存');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '满', '[VI] 满', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '满');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '满减享优惠', '[VI] 满减享优惠', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '满减享优惠');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '满员发货', '[VI] 满员发货', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '满员发货');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '滨江区', '[VI] 滨江区', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '滨江区');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '激活中', '[VI] 激活中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '激活中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '激活会员卡', '[VI] 激活会员卡', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '激活会员卡');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点', '[VI] 点', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击【立即注销】即代表您已经同意《用户注销协议》', '[VI] 点击【立即注销】即代表您已经同意《用户注销协议》', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击【立即注销】即代表您已经同意《用户注销协议》');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击修改密码', '[VI] 点击修改密码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击修改密码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击兑换卡密', '[VI] 点击兑换卡密', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击兑换卡密');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击前往', '[VI] 点击前往', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击前往');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击加载更多', '[VI] 点击加载更多', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击加载更多');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击右上角', '[VI] 点击右上角', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击右上角');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击复制', '[VI] 点击复制', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击复制');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击复制网址去浏览器中打开', '[VI] 点击复制网址去浏览器中打开', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击复制网址去浏览器中打开');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击授权登录您的客服工作台', '[VI] 点击授权登录您的客服工作台', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击授权登录您的客服工作台');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击搜索名称', '[VI] 点击搜索名称', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击搜索名称');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击更换手机号码', '[VI] 点击更换手机号码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击更换手机号码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击查看', '[VI] 点击查看', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击查看');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击管理', '[VI] 点击管理', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击管理');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击绑定手机号', '[VI] 点击绑定手机号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击绑定手机号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点击阅读', '[VI] 点击阅读', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点击阅读');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点经验', '[VI] 点经验', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点经验');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点经验/人', '[VI] 点经验/人', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点经验/人');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点经验/元', '[VI] 点经验/元', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点经验/元');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点解锁', '[VI] 点解锁', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点解锁');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '点，需达到', '[VI] 点，需达到', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '点，需达到');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '热门推荐', '[VI] 热门推荐', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '热门推荐');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '热门搜索', 'Tìm kiếm phổ biến', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '热门搜索');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '热门榜单', '[VI] 热门榜单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '热门榜单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '版本是否过低，升级试试吧', '[VI] 版本是否过低，升级试试吧', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '版本是否过低，升级试试吧');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '物流公司', '[VI] 物流公司', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '物流公司');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '生成海报', '[VI] 生成海报', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '生成海报');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '用户ID', '[VI] 用户ID', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '用户ID');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '用户协议', 'Thỏa thuận người dùng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '用户协议');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '用户备注', '[VI] 用户备注', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '用户备注');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '用户姓名', '[VI] 用户姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '用户姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '用户昵称', '[VI] 用户昵称', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '用户昵称');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '用户点击取消', '[VI] 用户点击取消', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '用户点击取消');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '用户点击确定', '[VI] 用户点击确定', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '用户点击确定');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '用户离开了', '[VI] 用户离开了', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '用户离开了');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '用户等级优惠', '[VI] 用户等级优惠', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '用户等级优惠');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '用户评价', '[VI] 用户评价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '用户评价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '由平台为您提供配送服务', '[VI] 由平台为您提供配送服务', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '由平台为您提供配送服务');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '申请中', '[VI] 申请中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '申请中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '申请图片', '[VI] 申请图片', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '申请图片');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '申请开票', '[VI] 申请开票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '申请开票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '申请成功', '[VI] 申请成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '申请成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '申请时间', '[VI] 申请时间', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '申请时间');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '申请理由', '[VI] 申请理由', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '申请理由');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '申请退款', 'Yêu cầu hoàn tiền', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '申请退款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '申请退款中', '[VI] 申请退款中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '申请退款中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '电子专用发票', '[VI] 电子专用发票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '电子专用发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '电子普通发票', '[VI] 电子普通发票', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '电子普通发票');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '电子面单', '[VI] 电子面单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '电子面单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '电子面单打印', '[VI] 电子面单打印', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '电子面单打印');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '番禺区', '[VI] 番禺区', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '番禺区');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '登录', 'Đăng nhập', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '登录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '登录中', '[VI] 登录中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '登录中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '登录失败', '[VI] 登录失败', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '登录失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '登录成功', '[VI] 登录成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '登录成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '登录注册需绑定手机号', '[VI] 登录注册需绑定手机号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '登录注册需绑定手机号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '直播中', '[VI] 直播中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '直播中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '省', '[VI] 省', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '省');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '真实姓名', '[VI] 真实姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '真实姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '知道了', '[VI] 知道了', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '知道了');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '砍价', 'Mặc cả', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '砍价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '砍价列表', 'Danh sách mặc cả', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '砍价列表');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '砍价帮', '[VI] 砍价帮', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '砍价帮');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '砍价成功', 'Mặc cả thành công', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '砍价成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '砍价活动', 'Hoạt động mặc cả', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '砍价活动');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '砍价海报', '[VI] 砍价海报', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '砍价海报');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '砍价规则', '[VI] 砍价规则', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '砍价规则');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '砍价详情', 'Chi tiết mặc cả', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '砍价详情');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '砍掉', 'Mặc cả được', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '砍掉');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确定', 'Xác định', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确定');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确定删除该员工?', '[VI] 确定删除该员工?', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确定删除该员工?');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确定删除该订单', '[VI] 确定删除该订单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确定删除该订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确定核销', '[VI] 确定核销', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确定核销');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确定清楚本地缓存数据吗', '[VI] 确定清楚本地缓存数据吗', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确定清楚本地缓存数据吗');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确定要核销此订单吗', '[VI] 确定要核销此订单吗', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确定要核销此订单吗');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确认', 'Xác nhận', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确认');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确认付款', '[VI] 确认付款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确认付款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确认修改', '[VI] 确认修改', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确认修改');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确认取消该订单', '[VI] 确认取消该订单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确认取消该订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确认提交', '[VI] 确认提交', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确认提交');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确认支付', '[VI] 确认支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确认支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确认收货', 'Xác nhận nhận hàng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确认收货');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确认新密码', '[VI] 确认新密码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确认新密码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确认激活', '[VI] 确认激活', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确认激活');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确认绑定', '[VI] 确认绑定', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确认绑定');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确认退出登录', '[VI] 确认退出登录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确认退出登录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '确认退款', '[VI] 确认退款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '确认退款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '礼品附加费', '[VI] 礼品附加费', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '礼品附加费');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '礼品附加费用', '[VI] 礼品附加费用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '礼品附加费用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '礼物已失效', '[VI] 礼物已失效', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '礼物已失效');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '礼物已被领取', '[VI] 礼物已被领取', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '礼物已被领取');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '种规格可选', '[VI] 种规格可选', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '种规格可选');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '秒', 'Giây', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '秒');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '秒杀', 'Giảm giá chớp nhoáng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '秒杀');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '积分', 'Điểm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '积分中心', '[VI] 积分中心', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '积分中心');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '积分兑换', 'Đổi điểm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '积分兑换');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '积分在', '[VI] 积分在', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '积分在');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '积分抵扣', 'Khấu trừ điểm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '积分抵扣');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '积分抽奖', '[VI] 积分抽奖', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '积分抽奖');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '积分数', '[VI] 积分数', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '积分数');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '积分规则', 'Quy tắc điểm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '积分规则');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '税号', 'Mã số thuế', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '税号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即下单', '[VI] 立即下单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即下单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即付款', '[VI] 立即付款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即付款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即体验', '[VI] 立即体验', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即体验');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即使用', '[VI] 立即使用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即使用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即保存', '[VI] 立即保存', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即保存');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即修改', '[VI] 立即修改', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即修改');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即充值', '[VI] 立即充值', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即充值');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即兑换', 'Đổi ngay', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即兑换');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即分享', '[VI] 立即分享', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即分享');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即升级', '[VI] 立即升级', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即升级');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即参与', '[VI] 立即参与', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即参与');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即参与砍价', '[VI] 立即参与砍价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即参与砍价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即开团', '[VI] 立即开团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即开团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即开通', 'Mở ngay', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即开通');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即提现', '[VI] 立即提现', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即提现');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即支付', 'Thanh toán ngay', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即核销', '[VI] 立即核销', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即核销');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即注销', '[VI] 立即注销', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即注销');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即激活', '[VI] 立即激活', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即激活');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即登录', '[VI] 立即登录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即登录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即签到', '[VI] 立即签到', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即签到');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即续费', '[VI] 立即续费', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即续费');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即评价', '[VI] 立即评价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即评价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即试用', '[VI] 立即试用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即试用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即购买', 'Mua ngay', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即购买');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即转入', '[VI] 立即转入', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即转入');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即退款', '[VI] 立即退款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即退款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即预定', '[VI] 立即预定', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即预定');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '立即领取', 'Nhận ngay', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '立即领取');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '等待支付中', '[VI] 等待支付中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '等待支付中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '签到', 'Điểm danh', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '签到');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '签到功能已关闭', '[VI] 签到功能已关闭', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '签到功能已关闭');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '签到成功', '[VI] 签到成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '签到成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '签到立即获取', '[VI] 签到立即获取', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '签到立即获取');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '签到领积分', 'Điểm danh nhận điểm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '签到领积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '管理', 'Quản lý', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '管理');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '类型', '[VI] 类型', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '类型');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '精品推荐', 'Đề xuất đặc biệt', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '精品推荐');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '累积已提', '[VI] 累积已提', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '累积已提');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '累计充值(元)', '[VI] 累计充值(元)', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '累计充值(元)');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '累计推广订单', '[VI] 累计推广订单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '累计推广订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '累计消费', '[VI] 累计消费', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '累计消费');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '累计消费(元)', '[VI] 累计消费(元)', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '累计消费(元)');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '累计积分', '[VI] 累计积分', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '累计积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '累计销售', '[VI] 累计销售', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '累计销售');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '累计销量', '[VI] 累计销量', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '累计销量');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '纳税人识别号', '[VI] 纳税人识别号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '纳税人识别号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '纸质发票开出后将以邮寄形式交付', '[VI] 纸质发票开出后将以邮寄形式交付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '纸质发票开出后将以邮寄形式交付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '线上下单，到店自提', '[VI] 线上下单，到店自提', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '线上下单，到店自提');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '线下付款,未支付', '[VI] 线下付款,未支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '线下付款,未支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '线下支付', 'Thanh toán ngoại tuyến', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '线下支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '线下支付已关闭，请点击确认按钮返回主页', '[VI] 线下支付已关闭，请点击确认按钮返回主页', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '线下支付已关闭，请点击确认按钮返回主页');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '经验累积', '[VI] 经验累积', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '经验累积');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '绑定', 'Liên kết', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '绑定');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '绑定成功', '[VI] 绑定成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '绑定成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '绑定手机号', '[VI] 绑定手机号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '绑定手机号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '结束', '[VI] 结束', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '结束');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '继续砍价', '[VI] 继续砍价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '继续砍价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '续费会员', '[VI] 续费会员', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '续费会员');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '缓存大小', '[VI] 缓存大小', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '缓存大小');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '缓存清理完成', '[VI] 缓存清理完成', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '缓存清理完成');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '编辑', 'Chỉnh sửa', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '编辑');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '缺少参数', '[VI] 缺少参数', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '缺少参数');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '缺少参数无法查看商品', '[VI] 缺少参数无法查看商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '缺少参数无法查看商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '缺少参数无法查看订单兑换状态', '[VI] 缺少参数无法查看订单兑换状态', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '缺少参数无法查看订单兑换状态');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '缺少参数无法查看订单支付状态', '[VI] 缺少参数无法查看订单支付状态', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '缺少参数无法查看订单支付状态');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '缺少支付参数', '[VI] 缺少支付参数', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '缺少支付参数');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '缺少经纬度信息无法查看地图', '[VI] 缺少经纬度信息无法查看地图', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '缺少经纬度信息无法查看地图');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '缺少经纬度信息无法查看地图！', '[VI] 缺少经纬度信息无法查看地图！', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '缺少经纬度信息无法查看地图！');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '缺少订单号', '[VI] 缺少订单号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '缺少订单号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '缺少订单号无法取消订单', '[VI] 缺少订单号无法取消订单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '缺少订单号无法取消订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '缺少订单号无法查看订单详情', '[VI] 缺少订单号无法查看订单详情', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '缺少订单号无法查看订单详情');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '网络连接断开', '[VI] 网络连接断开', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '网络连接断开');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '联系信息', '[VI] 联系信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '联系信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '联系客服', '[VI] 联系客服', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '联系客服');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '联系电话', '[VI] 联系电话', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '联系电话');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '联系邮箱', '[VI] 联系邮箱', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '联系邮箱');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '自定义', '[VI] 自定义', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '自定义');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '获取中', '[VI] 获取中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '获取中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '获取头像', '[VI] 获取头像', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '获取头像');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '获取您的昵称、头像', '[VI] 获取您的昵称、头像', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '获取您的昵称、头像');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '获取手机号', '[VI] 获取手机号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '获取手机号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '获取手机号授权', '[VI] 获取手机号授权', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '获取手机号授权');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '获取抽奖信息', '[VI] 获取抽奖信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '获取抽奖信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '获取授权', '[VI] 获取授权', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '获取授权');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '获取用户信息失败', '[VI] 获取用户信息失败', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '获取用户信息失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '获取验证码', '[VI] 获取验证码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '获取验证码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '获奖时间', '[VI] 获奖时间', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '获奖时间');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '获得', '[VI] 获得', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '获得');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '营业时间', '[VI] 营业时间', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '营业时间');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '营业额（元）', '[VI] 营业额（元）', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '营业额（元）');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '虚拟发货', '[VI] 虚拟发货', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '虚拟发货');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '虚拟备注', '[VI] 虚拟备注', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '虚拟备注');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单', 'Đơn hàng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单中心', '[VI] 订单中心', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单中心');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单信息', '[VI] 订单信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单信息不存在', '[VI] 订单信息不存在', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单信息不存在');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单创建成功', '[VI] 订单创建成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单创建成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单包裹', '[VI] 订单包裹', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单包裹');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单号', '[VI] 订单号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单备注', '[VI] 订单备注', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单备注');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单已支付', '[VI] 订单已支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单已支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单排序', '[VI] 订单排序', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单排序');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单支付中', '[VI] 订单支付中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单支付中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单支付成功', '[VI] 订单支付成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单支付成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单数', '[VI] 订单数', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单数');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单未备注，点击添加备注信息', '[VI] 订单未备注，点击添加备注信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单未备注，点击添加备注信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单状态', '[VI] 订单状态', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单状态');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单编号', 'Số đơn hàng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单编号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '订单量（份）', '[VI] 订单量（份）', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '订单量（份）');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '设为默认', 'Đặt làm mặc định', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '设为默认');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '设置为默认地址', '[VI] 设置为默认地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '设置为默认地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '设置为默认抬头', '[VI] 设置为默认抬头', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '设置为默认抬头');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '设置成功', '[VI] 设置成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '设置成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '设置收货地址', '[VI] 设置收货地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '设置收货地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '设置新密码', '[VI] 设置新密码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '设置新密码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '评价', '[VI] 评价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '评价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '评价完成', '[VI] 评价完成', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '评价完成');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '评分', '[VI] 评分', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '评分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '试用', '[VI] 试用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '试用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '该产品没有更多库存了', '[VI] 该产品没有更多库存了', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '该产品没有更多库存了');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '该商品已下架', '[VI] 该商品已下架', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '该商品已下架');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '该商品已失效', '[VI] 该商品已失效', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '该商品已失效');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '该商品每人限购', '[VI] 该商品每人限购', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '该商品每人限购');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '该订单暂未支付', '[VI] 该订单暂未支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '该订单暂未支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '详情', 'Chi tiết', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '详情');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '详细地址', 'Địa chỉ chi tiết', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '详细地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '详细数据', '[VI] 详细数据', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '详细数据');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '语言切换', '[VI] 语言切换', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '语言切换');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '说明: 每笔佣金的冻结期为', '[VI] 说明: 每笔佣金的冻结期为', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '说明: 每笔佣金的冻结期为');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请上传', '[VI] 请上传', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请上传');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请上传头像', '[VI] 请上传头像', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请上传头像');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请上传营业执照', '[VI] 请上传营业执照', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请上传营业执照');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请上传营业执照及行业相关资质证明图片', '[VI] 请上传营业执照及行业相关资质证明图片', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请上传营业执照及行业相关资质证明图片');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请先选择退货商品', '[VI] 请先选择退货商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请先选择退货商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请先阅读并同意协议', '[VI] 请先阅读并同意协议', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请先阅读并同意协议');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请勾选并同意入驻协议', '[VI] 请勾选并同意入驻协议', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请勾选并同意入驻协议');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请勿重复点击', '[VI] 请勿重复点击', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请勿重复点击');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写', '[VI] 请填写', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写${item.title}', '[VI] 请填写${item.title}', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写${item.title}');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写你对宝贝的心得', '[VI] 请填写你对宝贝的心得', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写你对宝贝的心得');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写具体地址', '[VI] 请填写具体地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写具体地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写内容', '[VI] 请填写内容', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写内容');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写卡号', '[VI] 请填写卡号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写卡号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写图片验证码', '[VI] 请填写图片验证码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写图片验证码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写备注信息', '[VI] 请填写备注信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写备注信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写姓名', '[VI] 请填写姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写密码', '[VI] 请填写密码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写密码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写微信号', '[VI] 请填写微信号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写微信号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写您的微信账号', '[VI] 请填写您的微信账号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写您的微信账号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写您的支付宝账号', '[VI] 请填写您的支付宝账号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写您的支付宝账号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写您的真实姓名', '[VI] 请填写您的真实姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写您的真实姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写手机号码', '[VI] 请填写手机号码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写手机号码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写持卡人姓名', '[VI] 请填写持卡人姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写持卡人姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写提现金额', '[VI] 请填写提现金额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写提现金额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写支付宝号', '[VI] 请填写支付宝号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写支付宝号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写支付宝绑定的真实姓名', '[VI] 请填写支付宝绑定的真实姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写支付宝绑定的真实姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写收货人姓名', '[VI] 请填写收货人姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写收货人姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写联系人或联系人电话', '[VI] 请填写联系人或联系人电话', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写联系人或联系人电话');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写联系电话', '[VI] 请填写联系电话', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写联系电话');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写详细地址', '[VI] 请填写详细地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写详细地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写账号', '[VI] 请填写账号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写账号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请填写验证码', '[VI] 请填写验证码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请填写验证码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请按以上退货信息将商品退回', '[VI] 请按以上退货信息将商品退回', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请按以上退货信息将商品退回');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请检查情况', '[VI] 请检查情况', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请检查情况');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请正确输入您的手机号', '[VI] 请正确输入您的手机号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请正确输入您的手机号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请正确输入您的联系邮箱', '[VI] 请正确输入您的联系邮箱', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请正确输入您的联系邮箱');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请正确输入您的银行账号', '[VI] 请正确输入您的银行账号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请正确输入您的银行账号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请正确输入纳税人识别号', '[VI] 请正确输入纳税人识别号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请正确输入纳税人识别号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请添加备注（150字以内）', '[VI] 请添加备注（150字以内）', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请添加备注（150字以内）');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请点击授权', '[VI] 请点击授权', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请点击授权');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请点击登录', '[VI] 请点击登录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请点击登录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请绑定手机号后，继续操作', '[VI] 请绑定手机号后，继续操作', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请绑定手机号后，继续操作');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请联系管理员获取退货地址', '[VI] 请联系管理员获取退货地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请联系管理员获取退货地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入', 'Vui lòng nhập', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入代理商名称', '[VI] 请输入代理商名称', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入代理商名称');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入代理商邀请码', '[VI] 请输入代理商邀请码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入代理商邀请码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入内容', '[VI] 请输入内容', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入内容');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入分销员姓名', '[VI] 请输入分销员姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入分销员姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入卡号', '[VI] 请输入卡号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入卡号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入卡密', '[VI] 请输入卡密', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入卡密');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入备注', '[VI] 请输入备注', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入备注');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入姓名', '[VI] 请输入姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入您所在的企业地址', '[VI] 请输入您所在的企业地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入您所在的企业地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入您的企业电话', '[VI] 请输入您的企业电话', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入您的企业电话');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入您的开户银行', '[VI] 请输入您的开户银行', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入您的开户银行');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入您的手机号', '[VI] 请输入您的手机号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入您的手机号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入您的联系邮箱', '[VI] 请输入您的联系邮箱', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入您的联系邮箱');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入您的银行账号', '[VI] 请输入您的银行账号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入您的银行账号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入手机号', '[VI] 请输入手机号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入手机号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入持卡人姓名', '[VI] 请输入持卡人姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入持卡人姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入支付金额', '[VI] 请输入支付金额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入支付金额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入新密码', '[VI] 请输入新密码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入新密码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入昵称', '[VI] 请输入昵称', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入昵称');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入核销码', '[VI] 请输入核销码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入核销码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入正确的', '[VI] 请输入正确的', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入正确的');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入正确的手机号码', '[VI] 请输入正确的手机号码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入正确的手机号码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入正确的核销码', '[VI] 请输入正确的核销码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入正确的核销码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入正确的账号', '[VI] 请输入正确的账号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入正确的账号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入正确的金额', '[VI] 请输入正确的金额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入正确的金额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入正确的验证码', '[VI] 请输入正确的验证码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入正确的验证码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入比例', '[VI] 请输入比例', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入比例');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入申请理由', '[VI] 请输入申请理由', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入申请理由');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入百分比', '[VI] 请输入百分比', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入百分比');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入纳税人识别号', '[VI] 请输入纳税人识别号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入纳税人识别号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入联系电话', '[VI] 请输入联系电话', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入联系电话');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入要搜索的商品', '[VI] 请输入要搜索的商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入要搜索的商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入金额', '[VI] 请输入金额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入金额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入需要开具发票的企业名称', '[VI] 请输入需要开具发票的企业名称', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入需要开具发票的企业名称');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入需要开具发票的姓名', '[VI] 请输入需要开具发票的姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入需要开具发票的姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请输入验证码', '[VI] 请输入验证码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请输入验证码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请选择', 'Vui lòng chọn', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请选择');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请选择产品', '[VI] 请选择产品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请选择产品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请选择商品', '[VI] 请选择商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请选择商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请选择属性', '[VI] 请选择属性', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请选择属性');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请选择所在地区', '[VI] 请选择所在地区', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请选择所在地区');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请选择收货地址', '[VI] 请选择收货地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请选择收货地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请选择数量', '[VI] 请选择数量', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请选择数量');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请选择电子面单', '[VI] 请选择电子面单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请选择电子面单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请选择要支付的订单', '[VI] 请选择要支付的订单', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请选择要支付的订单');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请选择要购买的商品', '[VI] 请选择要购买的商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请选择要购买的商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请选择银行', '[VI] 请选择银行', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请选择银行');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请重新选择商品规格', '[VI] 请重新选择商品规格', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请重新选择商品规格');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '请阅读并同意分销员协议', '[VI] 请阅读并同意分销员协议', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '请阅读并同意分销员协议');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '谢谢你为我付款，还可以再去看看其他商品哟~', '[VI] 谢谢你为我付款，还可以再去看看其他商品哟~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '谢谢你为我付款，还可以再去看看其他商品哟~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '谢谢你帮我支付，么么哒~', '[VI] 谢谢你帮我支付，么么哒~', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '谢谢你帮我支付，么么哒~');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '账单记录', '[VI] 账单记录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '账单记录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '账号', '[VI] 账号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '账号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '账号注销', '[VI] 账号注销', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '账号注销');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '账号登录', '[VI] 账号登录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '账号登录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '账户充值', '[VI] 账户充值', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '账户充值');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '购买', '[VI] 购买', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '购买');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '购买即视为同意', '[VI] 购买即视为同意', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '购买即视为同意');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '购买商品', '[VI] 购买商品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '购买商品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '购买商品可获得对应的经验值', '[VI] 购买商品可获得对应的经验值', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '购买商品可获得对应的经验值');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '购买商品可获得积分奖励', '[VI] 购买商品可获得积分奖励', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '购买商品可获得积分奖励');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '购物折扣', '[VI] 购物折扣', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '购物折扣');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '购物数量', '[VI] 购物数量', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '购物数量');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '购物满', '[VI] 购物满', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '购物满');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '购物车', 'Giỏ hàng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '购物车');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '赚积分', 'Kiếm điểm', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '赚积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '赚积分抵现金', '[VI] 赚积分抵现金', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '赚积分抵现金');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '赠送', '[VI] 赠送', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '赠送');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '赠送优惠券', '[VI] 赠送优惠券', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '赠送优惠券');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '赠送积分', '[VI] 赠送积分', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '赠送积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '起', 'Từ', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '起');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '起购', '[VI] 起购', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '起购');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '超值优惠 限时专享', '[VI] 超值优惠 限时专享', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '超值优惠 限时专享');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '距离结束', '[VI] 距离结束', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '距离结束');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '距秒杀结束仅剩', '[VI] 距秒杀结束仅剩', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '距秒杀结束仅剩');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '跳过', 'Bỏ qua', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '跳过');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '转入余额', '[VI] 转入余额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '转入余额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '转入余额后无法再次转出，确认是否转入余额', '[VI] 转入余额后无法再次转出，确认是否转入余额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '转入余额后无法再次转出，确认是否转入余额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '转入成功', '[VI] 转入成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '转入成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '轻松赚积分', '[VI] 轻松赚积分', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '轻松赚积分');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '输入手机号', '[VI] 输入手机号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '输入手机号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '输入手机号码', '[VI] 输入手机号码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '输入手机号码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '输入验证码', '[VI] 输入验证码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '输入验证码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '过期，请尽快使用', '[VI] 过期，请尽快使用', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '过期，请尽快使用');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '运费', 'Phí vận chuyển', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '运费');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '返佣', '[VI] 返佣', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '返佣');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '返回商城首页', '[VI] 返回商城首页', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '返回商城首页');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '返回首页', '[VI] 返回首页', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '返回首页');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '还剩', '[VI] 还剩', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '还剩');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '还差', 'Còn thiếu', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '还差');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '进入商城', '[VI] 进入商城', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '进入商城');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '进行中', '[VI] 进行中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '进行中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '连接失败', '[VI] 连接失败', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '连接失败');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '连续签到', 'Điểm danh liên tục', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '连续签到');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '退出登录', '[VI] 退出登录', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '退出登录');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '退款', 'Hoàn tiền', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '退款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '退款中', '[VI] 退款中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '退款中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '退款原因', '[VI] 退款原因', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '退款原因');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '退款类型', '[VI] 退款类型', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '退款类型');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '退款金额', '[VI] 退款金额', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '退款金额');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '退货件数', '[VI] 退货件数', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '退货件数');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '退货并退款', '[VI] 退货并退款', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '退货并退款');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '送礼物', '[VI] 送礼物', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '送礼物');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '送给好友', '[VI] 送给好友', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '送给好友');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '送货', '[VI] 送货', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '送货');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '送货人', '[VI] 送货人', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '送货人');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '送货人电话', '[VI] 送货人电话', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '送货人电话');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '选择 在浏览器 打开，去支付宝支付', '[VI] 选择 在浏览器 打开，去支付宝支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '选择 在浏览器 打开，去支付宝支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '选择付款方式', '[VI] 选择付款方式', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '选择付款方式');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '选择其它地址', '[VI] 选择其它地址', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '选择其它地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '选择地址', 'Chọn địa chỉ', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '选择地址');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '选择线下付款方式', '[VI] 选择线下付款方式', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '选择线下付款方式');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '选规格', '[VI] 选规格', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '选规格');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '通用券', '[VI] 通用券', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '通用券');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '通用劵', '[VI] 通用劵', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '通用劵');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '通知于', '[VI] 通知于', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '通知于');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '通联支付', '[VI] 通联支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '通联支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '邀请', 'Mời', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '邀请');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '邀请好友', 'Mời bạn bè', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '邀请好友');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '邀请好友参团', '[VI] 邀请好友参团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '邀请好友参团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '邀请好友帮砍价', '[VI] 邀请好友帮砍价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '邀请好友帮砍价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '邀请好友注册商城可获得经验值', '[VI] 邀请好友注册商城可获得经验值', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '邀请好友注册商城可获得经验值');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '邀请您参团', '[VI] 邀请您参团', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '邀请您参团');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '邀请您帮忙砍价', '[VI] 邀请您帮忙砍价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '邀请您帮忙砍价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '邀请您砍价', '[VI] 邀请您砍价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '邀请您砍价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '邀请码', '[VI] 邀请码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '邀请码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '邮箱', '[VI] 邮箱', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '邮箱');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '邮费', '[VI] 邮费', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '邮费');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '部分退款中', '[VI] 部分退款中', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '部分退款中');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '配送人姓名', '[VI] 配送人姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '配送人姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '配送方式', '[VI] 配送方式', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '配送方式');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '配送核销码', '[VI] 配送核销码', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '配送核销码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '配送运费', '[VI] 配送运费', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '配送运费');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '重新提交', '[VI] 重新提交', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '重新提交');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '重新支付', '[VI] 重新支付', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '重新支付');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '重新申请', '[VI] 重新申请', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '重新申请');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '重新获取', '[VI] 重新获取', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '重新获取');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '重新购买', '[VI] 重新购买', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '重新购买');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '重新连接', '[VI] 重新连接', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '重新连接');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '重新选择', '[VI] 重新选择', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '重新选择');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '重选', '[VI] 重选', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '重选');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '金额排序', '[VI] 金额排序', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '金额排序');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '银行', '[VI] 银行', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '银行');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '银行卡', 'Thẻ ngân hàng', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '银行卡');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '银行账号', '[VI] 银行账号', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '银行账号');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '销量', 'Doanh số', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '销量');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '销量榜', '[VI] 销量榜', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '销量榜');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '错误信息', '[VI] 错误信息', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '错误信息');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '长按保存图片', '[VI] 长按保存图片', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '长按保存图片');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '长按图片可以保存到手机', '[VI] 长按图片可以保存到手机', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '长按图片可以保存到手机');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '限购', '[VI] 限购', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '限购');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '限量', '[VI] 限量', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '限量');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '限量剩余', '[VI] 限量剩余', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '限量剩余');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '隐私协议', 'Chính sách quyền riêng tư', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '隐私协议');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '隐私政策概要', '[VI] 隐私政策概要', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '隐私政策概要');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '需要开具发票的企业名称', '[VI] 需要开具发票的企业名称', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '需要开具发票的企业名称');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '需要开具发票的姓名', '[VI] 需要开具发票的姓名', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '需要开具发票的姓名');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '顺丰请输入单号 :收件人或寄件人手机号后四位', '[VI] 顺丰请输入单号 :收件人或寄件人手机号后四位', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '顺丰请输入单号 :收件人或寄件人手机号后四位');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '预告', '[VI] 预告', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '预告');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '预售', 'Bán trước', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '预售');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '预售价', '[VI] 预售价', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '预售价');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '预售活动', '[VI] 预售活动', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '预售活动');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '预售活动时间', '[VI] 预售活动时间', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '预售活动时间');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '预售结束后', '[VI] 预售结束后', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '预售结束后');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '预览', 'Xem trước', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '预览');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '领券下单·享购物优惠', '[VI] 领券下单·享购物优惠', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '领券下单·享购物优惠');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '领取', '[VI] 领取', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '领取');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '领取优惠券', '[VI] 领取优惠券', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '领取优惠券');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '领取后', '[VI] 领取后', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '领取后');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '领取成功', '[VI] 领取成功', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '领取成功');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '首发新品', '[VI] 首发新品', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '首发新品');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '首次登录会自动注册', '[VI] 首次登录会自动注册', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '首次登录会自动注册');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '首页', 'Trang chủ', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '首页');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '验证码', 'Mã xác minh', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '验证码');
INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) 
SELECT @vi_type_id, '默认', 'Mặc định', 'Imported via script', 0 
WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '默认');
