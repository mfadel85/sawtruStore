/** @format */

import React, { PureComponent } from "react";
import { View } from "react-native";
import { WebView } from 'react-native-webview';

import { Color, Styles, withTheme } from "@common";
import { CustomPage } from "@containers";
import { Menu, NavBarLogo } from "./IconNav";

@withTheme
export default class CustomPageScreen extends PureComponent {
  static navigationOptions = ({ navigation }) => {
    const headerStyle = navigation.getParam(
      "headerStyle",
      Styles.Common.toolbar()
    );
    const dark = navigation.getParam("dark", false);

    return {
      headerTitle: NavBarLogo({ navigation }),
      headerLeft: Menu(dark),

      headerTintColor: Color.headerTintColor,
      headerStyle,
      headerTitleStyle: Styles.Common.headerStyle,

      // use to fix the border bottom
      headerTransparent: true,
    };
  };

  componentWillMount() {
    const {
      theme: {
        colors: { background },
        dark,
      },
    } = this.props;

    this.props.navigation.setParams({
      headerStyle: Styles.Common.toolbar(background, dark),
      dark,
    });
  }

  componentWillReceiveProps(nextProps) {
    if (this.props.theme.dark !== nextProps.theme.dark) {
      const {
        theme: {
          colors: { background },
          dark,
        },
      } = nextProps;
      this.props.navigation.setParams({
        headerStyle: Styles.Common.toolbar(background, dark),
        dark,
      });
    }
  }

  render() {
    const { state } = this.props.navigation;
    if (typeof state.params === "undefined") {
      return <View />;
    }

    if (typeof state.params.url !== "undefined") {
      return (
        <View style={{ flex: 1 }}>
          <WebView startInLoadingState source={{ uri: state.params.url }} />
        </View>
      );
    }

    return (
      <View>
        <CustomPage id={state.params.id} />
      </View>
    );
  }
}
