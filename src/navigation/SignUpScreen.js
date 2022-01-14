/** @format */

import React, { Component } from "react";

import { SignUp } from "@containers";
import { Color, Languages, Styles, withTheme } from "@common";
import { Back, EmptyView } from "./IconNav";

@withTheme
export default class SignUpScreen extends Component {
  static navigationOptions = ({ navigation }) => {
    const headerStyle = navigation.getParam(
      "headerStyle",
      Styles.Common.toolbar()
    );
    const dark = navigation.getParam("dark", false);

    return {
      title: Languages.signup,
      headerLeft: Back(navigation, null, dark),
      headerRight: EmptyView(),

      headerTintColor: Color.headerTintColor,
      headerStyle,
      headerTitleStyle: Styles.Common.headerTitleStyle,
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
    const { state, navigate } = this.props.navigation;
    return <SignUp params={state.params} onBackCart={() => navigate("Cart")} onViewHomeScreen={() => navigate("Default")} />;
  }
}
