/**
 * Created by InspireUI on 01/03/2017.
 * An API for JSON API Auth Word Press plugin.
 * https://wordpress.org/plugins/json-api-auth/
 *
 * @format
 */

import { AppConfig } from "@common";
import { request, error } from "./../Omni";

const url = AppConfig.Opencart.url;
const isSecured = url.startsWith("https");
const secure = isSecured ? "" : "&insecure=cool";
const cookieLifeTime = 120960000000;

const WPUserAPI = {
  login: async (username, password) => {
    const _url = `${url}/api/mstore_user/generate_auth_cookie/?second=${cookieLifeTime}&username=${username}&password=${password}${secure}`;
    return await request(_url);
  },
  loginFacebook: async (token) => {
    const _url = `${url}/api/mstore_user/fb_connect/?second=${cookieLifeTime}&access_token=${token}${secure}`;
    return await request(_url);
  },

  register: async ({
    username,
    email,
    firstName,
    lastName,
    password = undefined,
  }) => {
    try {
      const nonce = await WPUserAPI.getNonce();
      const _url =
        `${`${url}/api/mstore_user/register/?` +
        `username=${username}` +
        `&email=${email}` +
        `&display_name=${`${firstName}+${lastName}`}` +
        `&first_name=${firstName}` +
        `&last_name=${lastName}`}${
        password ? `&user_pass=${password}` : ""
        }&nonce=${nonce}` + `&notify=both${secure}`;
      return await request(_url);
    } catch (err) {
      error(err);
      return { error: err };
    }
  },
  getNonce: async () => {
    const _url = `${url}/api/get_nonce/?controller=mstore_user&method=register`;
    const json = await request(_url);
    return json && json.nonce;
  },
};

export default WPUserAPI;
