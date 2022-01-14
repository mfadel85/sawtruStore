/** @format */

import React, { PureComponent } from "react";
import PropTypes from "prop-types";
import { StatusBar, View } from "react-native";
import { Constants, Tools, warn, Color, Styles, Device } from "@common";
import { SafeAreaView } from "react-navigation";

/**
|--------------------------------------------------
| Fixed safe area view on IphoneX, so just fix bottom screen
|--------------------------------------------------
*/
export default class SafeArea extends PureComponent {
  static propTypes = {
    backgroundColor: PropTypes.string.isRequired,
    barColor: PropTypes.string.isRequired,
    isSafeArea: PropTypes.bool.isRequired,
    isSafeAreaBottom: PropTypes.bool.isRequired,
    style: PropTypes.object,
  };

  static defaultProps = {
    backgroundColor: "transparent",
    barColor: "dark-content",
    isSafeArea: true,
    isSafeAreaBottom: false,
  };

  constructor(props) {
    super(props);

    this.state = {
      barColor:
        props.backgroundColor !== "transparent"
          ? "light-content"
          : "dark-content",
    };
  }

  render() {
    const {
      backgroundColor,
      isSafeArea,
      isSafeAreaBottom,
      children,
      style,
    } = this.props;
    const { barColor } = this.state;

    if (!isSafeArea) return children;

    return (
      <View style={{ flex: 1 }}>
        {children}

        {/* {isSafeAreaBottom && Device.isIphoneX && (
                    <View style={{
                        ...Styles.Common.viewCover
                    }} />
                )} */}
      </View>
    );
  }
}
