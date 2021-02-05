import request from '@/utils/request'
import Qs from 'qs'
export function getList(query) {
  return request({
    url: 'coupon',
    method: 'get',
    params: query
  })
}

export function create(data) {
  data = Qs.parse({
    data
  })
  data = data.data
  return request({
    url: 'coupon',
    method: 'post',
    data
  })
}

export function edit(data) {
  data = Qs.parse({
    data
  })
  data = data.data
  return request({
    url: 'coupon/' + data.id,
    method: 'post',
    data
  })
}

export function destroy(id) {
  return request({
    url: 'coupon/destroy/' + id,
    method: 'post'
  })
}
