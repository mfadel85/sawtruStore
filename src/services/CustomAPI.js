/** @format */
/**
 * TODO: need refactor
 */

import { Config, AppConfig } from "@common";
import { Platform } from "react-native";

/**
 * init class API
 * @param opt
 * @returns {WordpressAPI}
 * @constructor
 */
function WordpressAPI(opt) {
  if (!(this instanceof WordpressAPI)) {
    return new WordpressAPI(opt);
  }
  const newOpt = opt || {};
  this._setDefaultsOptions(newOpt);
}

/**
 * Default option
 * @param opt
 * @private
 */
WordpressAPI.prototype._setDefaultsOptions = async function (opt) {
  this.url = opt.url ? opt.url : AppConfig.Opencart.url;
};

WordpressAPI.prototype.createComment = async function (data, callback) {
  let requestUrl = `${this.url}/api/mstore_user/post_comment/?insecure=cool`;
  if (data) {
    requestUrl += `&${this.join(data, "&")}`;
  }
  return this._request(requestUrl, data, callback);
};

WordpressAPI.prototype.join = function (obj, separator) {
  const arr = [];
  for (const key in obj) {
    if (obj.hasOwnProperty(key)) {
      arr.push(`${key}=${obj[key]}`);
    }
  }
  return arr.join(separator);
};
WordpressAPI.prototype._request = function (url, callback) {
  const self = this;
  return fetch(url)
    .then((response) => response.text()) // Convert to text instead of res.json()
    .then((text) => {
      if (Platform.OS === "android") {
        text = text.replace(/\r?\n/g, "").replace(/[\u0080-\uFFFF]/g, ""); // If android , I've removed unwanted chars.
      }
      return text;
    })
    .then((response) => JSON.parse(response))

    .catch((error, data) => {
      // console.log('1=error network -', error, data);
    })
    .then((responseData) => {
      if (typeof callback === "function") {
        callback();
      }
      // console.log('request result from ' + url, responseData);

      return responseData;
    })
    .catch((error) => {
      // console.log('2=error network -- ', error.message);
    });
};

export default new WordpressAPI();
