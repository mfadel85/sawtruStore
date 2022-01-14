/** @format */

import React from "react";
import { ScrollView } from "react-native";
import { connect } from "react-redux";

import { log } from "@app/Omni";
import { withTheme } from "@common";
import Footer from "./Footer";
import ShippingAddress from "./ShippingAddress";
import LineItemsAndPrice from "./LineItemsAndPrice";
import OrderStatus from "./OrderStatus";
import styles from "./styles";

class OrderDetail extends React.PureComponent {
  render() {
    const { order, theme } = this.props;

    return (
      <ScrollView
        style={styles.container(theme.colors.background)}
        contentContainerStyle={styles.contentContainer}>
        <LineItemsAndPrice order={order} theme={theme} />
        <OrderStatus order={order} theme={theme} />
        <ShippingAddress shipping={order.shipping} theme={theme} />
        <Footer order={order} />
      </ScrollView>
    );
  }
}

const mapStateToProps = ({ carts }, ownProps) => {
  const order = carts.myOrders.find((o, i) => o.id === ownProps.id);

  return { carts, order };
};

export default connect(mapStateToProps)(withTheme(OrderDetail));
