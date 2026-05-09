import request from '@/libs/request';

export function noticeChannelList(params) {
  return request({
    url: 'setting/notice_channel/index',
    method: 'get',
    params,
  });
}

export function noticeChannelSave(data) {
  return request({
    url: 'setting/notice_channel/save',
    method: 'post',
    data,
  });
}

export function noticeChannelUpdate(id, data) {
  return request({
    url: `setting/notice_channel/update/${id}`,
    method: 'post',
    data,
  });
}

export function noticeChannelSetStatus(id, status) {
  return request({
    url: `setting/notice_channel/set_status/${id}/${status}`,
    method: 'put',
  });
}

export function noticeChannelDelete(id) {
  return request({
    url: `setting/notice_channel/delete/${id}`,
    method: 'delete',
  });
}

export function noticeChannelTestTelegram(data) {
  return request({
    url: 'setting/notice_channel/test_telegram',
    method: 'post',
    data,
  });
}

