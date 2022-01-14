/**
 * Created by InspireUI on 03/03/2017.
 *
 * @format
 */

import React from "react";
import PropTypes from "prop-types";
import {
  View,
  Text,
  StyleSheet,
  Image,
  TouchableOpacity,
  Platform,
  LayoutAnimation,
} from "react-native";
import { connect } from "react-redux";

import { Color, Images, Styles, withTheme, Config } from "@common";
import { DisplayMode } from "@redux/CategoryRedux";

const controlBarHeight = 50;

class ControlBar extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      hideSubCategory: true,
    };
  }

  shouldComponentUpdate() {
    LayoutAnimation.configureNext(LayoutAnimation.Presets.easeInEaseOut);
    return true;
  }

  render() {
    const {
      switchDisplayMode,
      categories,
      openCategoryPicker,
      isVisible,
      name,
      theme: {
        colors: { background, text },
        dark: isDark,
      },
    } = this.props;

    return (
      <View
        style={[
          styles.container,
          {
            height: isVisible ? controlBarHeight : 0,
            backgroundColor: background,
          },
        ]}>
        <TouchableOpacity
          onPress={openCategoryPicker}
          style={styles.iconAndTextWrap}>
          <Image
            source={Images.IconFilter}
            style={[
              styles.iconStyle,
              styles.dark,
              isDark && { tintColor: "#fff" },
            ]}
          />
          <Text style={[styles.text, { color: text }]}>{name}</Text>
        </TouchableOpacity>
        <View style={{ flexDirection: "row", alignItems: "center" }}>
          {Platform.OS == "ios" && (
            <TouchableOpacity
              onPress={() => switchDisplayMode(DisplayMode.CardMode)}
              style={styles.modeButton}>
              <Image
                source={Images.IconCard}
                style={[
                  styles.iconStyle,
                  categories.displayMode === DisplayMode.CardMode &&
                    styles.dark,
                  isDark && { tintColor: "#fff" },
                ]}
              />
            </TouchableOpacity>
          )}

          <TouchableOpacity
            onPress={() => switchDisplayMode(DisplayMode.ListMode)}
            style={styles.modeButton}>
            <Image
              source={Images.IconList}
              style={[
                styles.iconStyle,
                categories.displayMode === DisplayMode.ListMode && styles.dark,
                isDark && { tintColor: "#fff" },
              ]}
            />
          </TouchableOpacity>

          <TouchableOpacity
            onPress={() => switchDisplayMode(DisplayMode.GridMode)}
            style={styles.modeButton}>
            <Image
              source={Images.IconGrid}
              style={[
                styles.iconStyle,
                categories.displayMode === DisplayMode.GridMode && styles.dark,
                isDark && { tintColor: "#fff" },
              ]}
            />
          </TouchableOpacity>
        </View>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    height: controlBarHeight,
    backgroundColor: Color.category.navigationBarColor,
    borderColor: Color.lightDivide,
    borderTopWidth: 1,

    flexDirection: "row",
    justifyContent: "space-between",
    paddingRight: 5,

    ...Platform.select({
      ios: {
        borderBottomWidth: 1,
      },
      android: {
        elevation: 5,
      },
    }),
  },
  modeButton: {
    ...Styles.Common.ColumnCenter,
    borderColor: Color.lightDivide,
    borderLeftWidth: 1,
    width: controlBarHeight - 10,
    height: controlBarHeight - 10,
  },
  iconAndTextWrap: {
    ...Styles.Common.RowCenter,
    marginHorizontal: 20,
  },
  text: {
    color: Color.black,
    paddingLeft: 10,
  },
  iconStyle: {
    resizeMode: "contain",
    width: 18,
    height: 18,
    opacity: 0.2,
  },
  dark: {
    opacity: 0.9,
  },
});

ControlBar.propTypes = {
  switchDisplayMode: PropTypes.func.isRequired,
  openCategoryPicker: PropTypes.func.isRequired,
  isVisible: PropTypes.bool,
};

const mapStateToProps = (state) => {
  return { categories: state.categories };
};

function mergeProps(stateProps, dispatchProps, ownProps) {
  const { categories } = stateProps;
  const { dispatch } = dispatchProps;
  const { actions } = require("@redux/CategoryRedux");

  return {
    ...ownProps,
    ...stateProps,
    switchDisplayMode: (mode) => {
      dispatch(actions.switchDisplayMode(mode));
    },
  };
}

export default connect(
  mapStateToProps,
  undefined,
  mergeProps
)(withTheme(ControlBar));
