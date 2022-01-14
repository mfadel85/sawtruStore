import React, { StyleSheet, Dimensions } from "react-native";
import { Color, Constants, Styles } from "@common";

const { width, height } = Dimensions.get("window");

export default StyleSheet.create({
  container:{
    flexDirection:'row',
    backgroundColor: "#d0d0d0",
    width: width - 120*2 - 15*3
  }
});
