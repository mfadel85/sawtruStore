/** @format */

import React, { PureComponent } from "react";

import { CartModule } from "@containers";

export default class CartScreen extends PureComponent {
  static navigationOptions = ({ navigation }) => ({
    header: null,
  });

  render() {
    const { navigate } = this.props.navigation;

    return (
      <CartModule
        onMustLogin={() => {
          navigate("LoginScreen", { onCart: true });
        }}
        onBack={() => navigate("Default")}
        onFinishOrder={() => navigate("MyOrders")}
        onViewHome={() => navigate("Default")}
        onViewProduct={(product) => navigate("Detail", product)}
        navigation={this.props.navigation}
      />
    );
  }
}
