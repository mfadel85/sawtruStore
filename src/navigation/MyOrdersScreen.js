/** @format */

import React, { Component } from "react";
import PropTypes from "prop-types";

import { MyOrders } from "@containers";

export default class MyOrdersScreen extends Component {
  static navigationOptions = ({ navigation }) => ({
    header: null,
  });

  static propTypes = {
    navigation: PropTypes.object,
  };

  render() {
    const { navigate } = this.props.navigation;
    return (
      <MyOrders
        navigate={this.props.navigation}
        onViewHomeScreen={() => navigate("Default")}
        onViewOrderDetail={(id) => navigate("OrderDetailScreen", { id })}
      />
    );
  }
}
