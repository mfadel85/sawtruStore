/**
 * Created by InspireUI on 05/01/2017.
 *
 * @format 
 */

import { Platform } from "react-native";
import { Facebook } from "@expo";

import { Config } from "@common";
import { toast, log } from "@app/Omni";

class FacebookAPI {
  async login() {
    try {
      await Facebook.initializeAsync({
        appId: Config.appFacebookId,
      });
      const ask = await Facebook.logInWithReadPermissionsAsync({
        permissions: ["public_profile", "email"],
      });
  
      const { type } = ask;
  
      if (type === "success") {
        const { token } = ask;
        const response = await fetch(`https://graph.facebook.com/me?fields=email,name&access_token=${token}`);
        const json = await response.json();
        console.log('Logged in!', response );

        return json;
      }
      console.log('will return null');
      return null;
    } catch({message}) {
      console.log('MSG::',message);
      alert('Message!!!',{message});
    }

  }

  logout() {
    Facebook.logOut();
    log("Facebook logout!");
  }
  async getAccessToken() {
    return await Facebook.getCurrentFacebook();
  }

  async shareLink(link, desc) {
    const shareLinkContent = {
      contentType: "link",
      contentUrl: link,
      contentDescription: desc,
    };
    try {
      const canShow = await Facebook.canShow(shareLinkContent);
      if (canShow) {
        const result = await Facebook.show(shareLinkContent);
        if (!result.isCancelled) {
          toast("Post shared");
          log(`Share a post with id: ${result.postId}`);
        }
      }
    } catch (error) {
      toast("An error occurred. Please try again later");
      error(`Share post fail with error: ${error}`);
    }
  }
}

export default new FacebookAPI();
