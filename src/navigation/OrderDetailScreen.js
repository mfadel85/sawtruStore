/** @format */

import React, { PureComponent } from "react";
import PropTypes from "prop-types";
import { View } from "react-native";

import { Color, Styles, withTheme } from "@common";
import { OrderDetail } from "@containers";
import { Logo, Back } from "./IconNav";

@withTheme
export default class OrderDetailScreen extends PureComponent {
  static navigationOptions = ({ navigation }) => {
    const headerStyle = navigation.getParam(
      "headerStyle",
      Styles.Common.toolbar()
    );
    const dark = navigation.getParam("dark", false);

    return {
      headerTitle: Logo(),
      tabBarVisible: false,
      headerLeft: Back(navigation, null, dark),

      headerTintColor: Color.headerTintColor,
      headerStyle,
      headerTitleStyle: Styles.Common.headerStyle,
    };
  };

  static propTypes = {
    navigation: PropTypes.object.isRequired,
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
    const id = navigation.getParam("id", null);

    if (!id) return null;

    return <OrderDetail navigation={navigation} id={id} />;
  }
}
