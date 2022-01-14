/** @format */

import React, { PureComponent } from "react";
import { Text, TouchableOpacity, View, Image } from "react-native";
import { withTheme, Tools, Config } from "@common";
import {TouchableScale} from '@components'
import styles from "./styles";
import { LinearGradient } from "@expo";

class Item extends PureComponent {
  render() {
    const {
      item,
      label,
      onPress,
      type,
      theme: {
        colors: { text },
      },
    } = this.props;

    if (type == 1 || typeof type == "undefined") {
      return (
        <View style={styles.container}>
          <TouchableScale
            scaleTo={0.7}
            style={styles.wrap}
            onPress={() => onPress({ ...item, circle: true, name: label })}>
            <View style={[styles.background, {opacity: 0.08, backgroundColor: item.colors[0]} ]}/>

            <View style={styles.iconView}>
              <Image
                  source={item.image}
                  style={[styles.icon, { tintColor: item.colors[0] }]}/>
            </View>
            <Text style={[styles.title, { color: text }]}>{label}</Text>
          </TouchableScale>
        </View>
      );
    }

    return (
      <View style={styles.container}>
        <TouchableOpacity
          style={styles.wrap}
          activeOpacity={0.75}
          onPress={() => onPress({ ...item, circle: true, name: label })}>
          <LinearGradient colors={item.colors} style={styles.button}>
            <Image source={item.image} style={[styles.icon]} />
          </LinearGradient>
          <Text style={[styles.title, { color: text }]}>{label}</Text>
        </TouchableOpacity>
      </View>
    );
  }
}

export default withTheme(Item);
