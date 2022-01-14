/** @format */

import React, {PureComponent} from "react";
import {View, Dimensions} from "react-native";
import css from "./styles";
import {Constants, Config} from "@common";
import {AdMobBanner, AdMobInterstitial} from 'expo'

if (Config.showAdmobAds) {
  AdMobInterstitial.setAdUnitID(Config.AdMob.unitInterstitial)
}

export default class Index extends PureComponent {
  componentDidMount() {
    Config.AdMob.isShowInterstital && setTimeout(this.showInterstital, 12000)
  }

  showInterstital = () => {
    Config.showAdmobAds && AdMobInterstitial
      .requestAdAsync()
      .then(() => AdMobInterstitial.showAdAsync())
  }

  render() {
    return (
      <View style={css.body}>
        {Config.sshowAdmobAds && <AdMobBanner
          ref={(component) => (this._root = component)}
          bannerSize={'fullBanner'}
          adSize="banner"
          testDeviceIDs={__DEV__
          ? ['EMULATOR']
          : [Config.AdMob.deviceID]}
          adUnitID={Config.AdMob.unitID}/>}
      </View>
    );
  }
}
