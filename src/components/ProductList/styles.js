/** @format */

import { StyleSheet, Platform, Dimensions } from "react-native";
import { Constants } from "@common";

const { width, height } = Dimensions.get("window");

export default StyleSheet.create({
  flatlist: {
    flexWrap: "wrap",
    flexDirection: "row",
    paddingBottom: 10,
    paddingTop: Platform.OS === "ios" ? 70 : 70,
  },
  more: {
    width,
    alignItems: "center",
    marginBottom: 10,
    marginTop: 10,
  },
  spinView: {
    width,
    backgroundColor: "#fff",
    flex: 1,
    paddingTop: 20,
  },
  header: {
    position: "absolute",
    top: 0,
    left: 0,
    right: 0,
    backgroundColor: "rgba(255, 255, 255, 0.5)",
    overflow: "hidden",
    height: Constants.Window.headerHeight+20,
  },
  headerText: {
    fontSize: 22,
    fontFamily: Constants.fontHeader,
    width,
    marginBottom: 20,
    marginTop: 50,
    marginLeft: 15,
  },

  bannerImage: {
    width: width - 40,
    marginLeft: 20,
    borderRadius: 6,
    flex: 1,
    height: (25 * height) / 100,
    // resizeMode: 'cover',
  },
  headerView: {
    marginBottom: 20,
  },
  listView: {
    flex: 1,
    backgroundColor: "rgba(0,255,255,1)",
    ...Platform.select({
      android: {
        marginTop: 0,
      },
    }),
  },
});
