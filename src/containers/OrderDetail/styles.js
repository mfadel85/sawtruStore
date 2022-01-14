/** @format */

import { StyleSheet, Platform, Dimensions } from "react-native";
import { Color, Constants } from "@common";

const { width } = Dimensions.get("window");
const cardMargin = Constants.Dimension.ScreenWidth(0.05);

export default StyleSheet.create({
  container: (background) => ({
    backgroundColor: background,
  }),
  contentContainer: {
    marginHorizontal: cardMargin,
  },
  name: (text) => ({
    marginBottom: 4,
    color: text,
    width: Constants.Dimension.ScreenWidth(0.6),
  }),
  title: (text) => ({
    marginBottom: 4,
    color: text,
    width: Constants.Dimension.ScreenWidth(0.6),
    textTransform: "uppercase",
    fontWeight: "bold",
  }),
  text: (text) => ({
    marginBottom: 4,
    color: text,
    alignSelf: "center",
  }),
  header: {
    marginTop: 20,
    marginBottom: 10,
  },
  label: (text) => ({
    fontFamily: Constants.fontHeader,
    fontSize: 20,
    color: text,
  }),
  row: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 8,
  },
  rowLabel: {
    fontSize: 16,
    fontFamily: Constants.fontFamily,
  },
  itemContainer: {},
  lineItem: {
    marginBottom: cardMargin / 2,
    flexDirection: "row",
    justifyContent: "space-between",
  },
  footer: {
    flexDirection: "row",
    justifyContent: "center",
    marginTop: 20,
  },
  button: {
    width: "40%",
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 20,
  },
});
