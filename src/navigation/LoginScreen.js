/** @format */

import React, { PureComponent } from "react";

import { Login } from "@containers";
import { Color, Styles, withTheme } from "@common";
import { Back, EmptyView, Logo } from "./IconNav";

@withTheme
export default class LoginScreen extends PureComponent {
  static navigationOptions = ({ navigation }) => {
    const headerStyle = navigation.getParam(
      "headerStyle",
      Styles.Common.toolbar()
    );
    const dark = navigation.getParam("dark", false);

    return {
      headerLeft: Back(navigation, null, dark),
      headerRight: EmptyView(),
      headerTitle: Logo(),

      headerTintColor: Color.headerTintColor,
      headerStyle,
      headerTitleStyle: Styles.Common.headerStyle,
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
    const { navigate, state, goBack } = this.props.navigation;
    const isLogout = state.params ? state.params.isLogout : false;

    return (
      <Login
        statusBar
        navigation={this.props.navigation}
        onBack={goBack}
        isLogout={isLogout}
        onViewSignUp={(user) => navigate("SignUpScreen", user)}
        onViewCartScreen={() => navigate("CartScreen")}
        onViewHomeScreen={() => navigate("Default")}
      />
    );
  }
}
