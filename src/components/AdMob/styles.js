/** @format */

import React, { StyleSheet, Dimensions, PixelRatio } from "react-native";
import Color from "@common/Color";
import Constants from "@common/Constants";
const { width, height, scale } = Dimensions.get("window"),
  vw = width / 100,
  vh = height / 100,
  vmin = Math.min(vw, vh),
  vmax = Math.max(vw, vh);

export default StyleSheet.create({
  body: {
    height: 60,
    width: width,
  },
  adView: {
    width: width,
    height: 60,
    backgroundColor: "rgba(255,255,255, 0)",
    zIndex: 9999,
  },
});
