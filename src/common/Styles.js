/**
 * Created by InspireUI on 20/12/2016.
 *
 * @format
 */

import { Dimensions, Platform } from "react-native";

import Constants from "./Constants";
import Device from "./Device";
import Color from "./Color";
import Config from "./Config";
import Theme from "./Theme";

const { height, width, heightWindow } = Dimensions.get("window");

const Styles = {
  width: Dimensions.get("window").width,
  height: Platform.OS !== "ios" ? height : height - 20,
  navBarHeight: Platform !== "ios" ? height - heightWindow : 0,
  headerHeight: Platform.OS === "ios" ? 40 : 56,

  thumbnailRatio: 1.2, // Thumbnail ratio, the product display height = width * thumbnail ratio

  app: {
    flexGrow: 1,
    backgroundColor: Device.isIphoneX ? "#FFF" : "#000",
    paddingTop: Device.ToolbarHeight,
  },
  FontSize: {
    tiny: 12,
    small: 14,
    medium: 16,
    big: 18,
    large: 20,
  },
  IconSize: {
    TextInput: 25,
    ToolBar: 18,
    Inline: 20,
    SmallRating: 14,
  },
  FontFamily: {},
};

Styles.Common = {
  Column: {},
  ColumnCenter: {
    justifyContent: "center",
    alignItems: "center",
  },
  ColumnCenterTop: {
    alignItems: "center",
  },
  ColumnCenterBottom: {
    justifyContent: "flex-end",
    alignItems: "center",
  },
  ColumnCenterLeft: {
    justifyContent: "center",
    alignItems: "flex-start",
  },
  ColumnCenterRight: {
    justifyContent: "center",
    alignItems: "flex-end",
  },
  Row: {
    flexDirection: "row",

    ...Platform.select({
      ios: {
        top: !Config.showStatusBar
          ? Device.isIphoneX
            ? -20
            : -8
          : Device.isIphoneX
          ? -15
          : 0,
      },
      android: {
        top: 0,
      },
    }),
  },
  RowCenter: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
  },
  RowCenterTop: {
    flexDirection: "row",
    justifyContent: "center",
  },
  RowCenterBottom: {
    flexDirection: "row",
    alignItems: "flex-end",
    justifyContent: "center",
  },
  RowCenterLeft: {
    flexDirection: "row",
    alignItems: "center",
  },
  RowCenterRight: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "flex-end",
  },
  RowCenterBetween: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
  },
  // More traits

  IconSearchView: {
    backgroundColor: "rgba(255, 255, 255, 0.9)",
    marginBottom: 10,
    borderRadius: 50,

    shadowOffset: { width: 0, height: -4 },
    shadowColor: "rgba(0,0,0, .3)",
    elevation: 10,
    shadowOpacity: 0.1,
    zIndex: 9999,
  },
  IconSearch: {
    width: 20,
    height: 20,
    margin: 12,
    zIndex: 9999,
  },

  logo: {
    width: Platform.OS === "ios" ? 180 : 200,
    height: Platform.OS === "ios" ? 30 : 20,
    resizeMode: "contain",
    ...Platform.select({
      ios: {
        marginTop: Device.isIphoneX ? -40 : Config.showStatusBar ? -4 : -15,
      },
      android: {
        marginTop: 2,
        marginLeft: 10,
      },
    }),
  },

  toolbar: (backgroundColor, isDark) => ({
    zIndex: 1,
    // paddingLeft: 15,
    // paddingRight: 15,
    paddingTop: 4,
    borderBottomWidth: isDark ? 0 : 1,
    borderBottomColor: "transparent",
    ...Platform.select({
      ios: {
     
      },
      android: {
        height: 46,
        //paddingTop: 0,
        marginTop: 0,
      },
    }),
  }),

  headerStyle: {
    color: Color.category.navigationTitleColor,
    fontSize: 50,
    textAlign: "center",
    alignSelf: "center",
    flex: 1,
    height: 40,
    backgroundColor: "transparent",

    fontFamily: Constants.fontFamily,
    ...Platform.select({
      ios: {
        marginBottom: !Config.showStatusBar ? 14 : 0,
        marginTop: Device.isIphoneX ? -10 : 12,
      },
      android: {
        marginBottom: 4,
      },
    }),
  },
  headerTitleStyle: {
    color: Config.Theme.isDark
      ? Theme.dark.colors.text
      : Theme.dark.colors.text,
    fontSize: 16,
    height: 40,
    textAlign: "center",
    fontFamily: Constants.fontFamily,
    alignSelf: "center",
    ...Platform.select({
      ios: {
        marginBottom: !Config.showStatusBar ? 14 : 0,
        marginTop: Device.isIphoneX ? -10 : 12,
      },
      android: {
        marginTop: 25,
      },
    }),
  },
  headerStyleWishList: {
    color: Color.category.navigationTitleColor,
    fontSize: 16,
    textAlign: "center",
    alignSelf: "center",
    fontFamily: Constants.fontFamily,
    marginBottom: !Config.showStatusBar
      ? Device.isIphoneX
        ? 40
        : 15
      : Device.isIphoneX
      ? 25
      : 5,
  },
  toolbarIcon: {
    width: 16,
    height: 16,
    resizeMode: "contain",

    marginRight: 18,
    marginBottom: 12,
    marginLeft: 18,
    opacity: 0.8,
    ...Platform.select({
      ios: {
        top: !Config.showStatusBar
          ? Device.isIphoneX
            ? -20
            : -8
          : Device.isIphoneX
          ? -15
          : 0,
      },
      android: {
        top: 0,
      },
    }),
  },

  toolbarFloat: {
    position: "absolute",
    top: 0,
    borderBottomWidth: 0,
    zIndex: 999,
    width,

    ...Platform.select({
      ios: {
        backgroundColor: "transparent",
        marginTop: Config.showStatusBar
          ? Device.isIphoneX
            ? -15
            : 7
          : Device.isIphoneX
          ? -15
          : -3,
      },
      android: {
        backgroundColor: Config.Theme.isDark
          ? Theme.dark.colors.background
          : "#fff",
        height: 46,
        paddingTop: Config.showStatusBar ? 24 : 0,
      },
    }),
  },
  viewCover: {
    backgroundColor: "#FFF",
    zIndex: 99999,
    bottom: 0,
    left: 0,
    width,
    height: 20,
    // position: "absolute",
  },
  viewCoverWithoutTabbar: {
    backgroundColor: "#FFF",
    zIndex: 99999,
    bottom: 0,
    left: 0,
    width,
    height: 35,
    position: "absolute",
  },

  viewBack: {
    ...Platform.select({
      ios: {
        marginTop: Device.isIphoneX ? -25 : -5,
      },
    }),
  },
  toolbarIconBack: {
    width: 16,
    height: 16,
    resizeMode: "contain",

    marginRight: 18,
    marginBottom: 12,
    marginLeft: 18,
    opacity: 0.8,
    ...Platform.select({
      ios: {
        top: !Config.showStatusBar ? 4 : Device.isIphoneX ? 4 : 8,
      },
      android: {
        top: 0,
      },
    }),
  },
};

export default Styles;
