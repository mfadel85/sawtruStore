/** @format */

import React, { PureComponent } from "react";
import { Menu, EmptyView } from "./IconNav";

import { Images, Config, Constants, Color, Styles, Languages } from "@common";
import { WishList } from "@containers";

export default class WishListScreen extends PureComponent {
  static navigationOptions = ({ navigation }) => ({
    title: Languages.WishList,
    header: null,
  });

  render() {
    const { navigate } = this.props.navigation;
    // const rootNavigation = this.props.screenProps.rootNavigation;

    return (
      <WishList
        onViewProduct={(product) => navigate("Detail", product)}
        onViewHome={() => navigate("Default")}
      />
    );
  }
}
