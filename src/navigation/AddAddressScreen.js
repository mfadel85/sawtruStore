/** @format */

import React, { PureComponent } from "react";

import { Color, Styles, withTheme } from "@common";
import { AddAddress } from "@containers";
import { Back, EmptyView } from "./IconNav";

@withTheme
export default class AddressScreen extends PureComponent {
  static navigationOptions = ({ navigation }) => {
    const headerStyle = navigation.getParam(
      "headerStyle",
      Styles.Common.toolbar()
    );
    const dark = navigation.getParam("dark", false);

    return {
      headerLeft: Back(navigation, null, dark),
      headerRight: EmptyView(),

      headerTintColor: Color.headerTintColor,
      headerStyle,
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
    const { navigation } = this.props;

    return <AddAddress onBack={() => navigation.goBack()} />;
  }
}
