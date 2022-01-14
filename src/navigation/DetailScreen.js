/** @format */

import React, { PureComponent } from "react";
import PropTypes from "prop-types";
import { View } from "react-native";

import { Color, Styles, withTheme } from "@common";
import { SafeAreaView } from "@components";
import { Detail } from "@containers";
import { Logo, Back, CartWishListIcons } from "./IconNav";
import * as OpencartWorker from '@services/OpencartWorker'
import { warn } from "@app/Omni";
@withTheme
export default class DetailScreen extends PureComponent {
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
      headerRight: CartWishListIcons(navigation),

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
    const { state, navigate } = this.props.navigation;

    return (
      <SafeAreaView isSafeAreaBottom>
        <View style={{ flex: 1 }}>
          {typeof state.params !== "undefined" && (
            <Detail
              product={state.params.product}
              onViewCart={() => navigate("CartScreen")}
              onViewProductScreen={this.showProductDetail}
              navigation={this.props.navigation}
              onLogin={() => navigate("LoginScreen")}
            />
          )}
        </View>
      </SafeAreaView>
    );
  }

  showProductDetail = (product) => {
    this.props.navigation.setParams(product);
  }

}
