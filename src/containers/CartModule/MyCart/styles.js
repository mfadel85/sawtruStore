/** @format */

import { StyleSheet } from "react-native";
import { Color, Constants, Theme } from "@common";

export default StyleSheet.create({
  container: {
    flex: 1,
    // backgroundColor: "white",
  },
  list: {
    flex: 1,
  },
  hiddenRow: {
    flex: 1,
    backgroundColor: "red",
    alignItems: "flex-end",
    justifyContent: "center",
  },
  couponView: (isDark) => ({
    padding: 20,
    borderTopWidth: 10,
    borderColor: isDark ? Theme.dark.colors.background : Color.lightGrey,
  }),
  row: {
    flexDirection: "row",
    marginTop: 12,
    alignItems: "center",
  },
  couponInput: {
    flex: 1,
    color: "#000",
    height: 40,
    borderRadius: 20,
    backgroundColor: Color.lightGrey,
    marginRight: 20,
    paddingHorizontal: 15,
    borderWidth: 1,
    borderColor: Color.lightgrey,
  },
  btnApply: {
    width: 100,
    height: 40,
    borderRadius: 20,
    alignItems: "center",
    justifyContent: "center",
  },
  btnApplyText: {
    color: "white",
    position: "absolute",
    backgroundColor: "transparent",
  },
  couponMessage: {
    marginTop: 15,
    marginBottom: 15,
    textAlign: "center",
    color: Color.green,
  },
});
