/** @format */

import React from "react";
import { View, Text } from "react-native";

// import { log } from "@app/Omni";
import { Languages, Tools } from "@common";
import { ConfirmCheckout } from "@components";
import styles from "./styles";

export default class LineItemsAndPrice extends React.PureComponent {
  render() {
    const {
      order,
      theme: {
        colors: { text, primary },
      },
    } = this.props;

    return (
      <View>
        <View style={styles.header}>
          <Text style={styles.label(text)}>
            {Languages.OrderId}: #{order.id}
          </Text>
        </View>
        <View style={styles.itemContainer}>
          {order.line_items.map((o, i) => {
            return (
              <View key={i.toString()} style={styles.lineItem}>
                <Text
                  style={styles.name(text)}
                  numberOfLines={2}
                  ellipsizeMode="tail">
                  {o.name}
                </Text>
                <Text style={styles.text(text)}>{`x${o.quantity}`}</Text>
                <Text style={styles.text(text)}>
                  {Tools.getCurrecyFormatted(o.total)}
                </Text>
              </View>
            );
          })}
        </View>
        <ConfirmCheckout
          shippingMethod={order.shipping_total}
          totalPrice={order.sub_total}
          style={{ margin: 0 }}
          totalStyle={{ color: primary, fontWeight: "bold" }}
          labelStyle={{ color: text, fontWeight: "bold" }}
        />
      </View>
    );
  }
}
