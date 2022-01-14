/** @format */

import React, { PureComponent } from "react";
import { Ionicons } from "@expo/vector-icons";
import { StyleSheet, View, Text, Image, I18nManager } from "react-native";
import { LinearGradient } from "@expo";
import AppIntroSlider from "react-native-app-intro-slider";
import styles from "./styles";
import { Config } from "@common";
import { connect } from "react-redux";

class AppIntro extends PureComponent {
  _renderItem = ({item}) => (
    <LinearGradient
      style={[
        styles.mainContent,
        {
          paddingTop: item.topSpacer,
          paddingBottom: item.bottomSpacer,
          width: item.width,
          height: item.height,
        },
      ]}
      colors={item.colors}
      start={{ x: 0, y: 0.1 }}
      end={{ x: 0.1, y: 1 }}>
      <Ionicons
        style={{ backgroundColor: "transparent" }}
        name={item.icon}
        size={200}
        color="white"
      />
      <View>
        <Text style={styles.title}>{item.title}</Text>
        <Text style={styles.text}>{item.text}</Text>
      </View>
    </LinearGradient>
  );

  _renderNextButton = () => {
    return (
      <View style={styles.buttonCircle}>
        <Ionicons
          name={
            I18nManager.isRTL ? "md-arrow-round-back" : "md-arrow-round-forward"
          }
          color="rgba(255, 255, 255, .9)"
          size={24}
          style={{ backgroundColor: "transparent" }}
        />
      </View>
    );
  };

  _renderDoneButton = () => {
    return (
      <View style={styles.buttonCircle}>
        <Ionicons
          name="md-checkmark"
          color="rgba(255, 255, 255, .9)"
          size={24}
          style={{ backgroundColor: "transparent" }}
        />
      </View>
    );
  };

  render() {
    return (
      <AppIntroSlider
        data={Config.intro}
        renderItem={this._renderItem}
        renderDoneButton={this._renderDoneButton}
        renderNextButton={this._renderNextButton}
        onDone={this.props.finishIntro}
      />
    );
  }
}

const mapDispatchToProps = (dispatch) => {
  const { actions } = require("@redux/UserRedux");
  return {
    finishIntro: () => dispatch(actions.finishIntro()),
  };
};
export default connect(
  null,
  mapDispatchToProps
)(AppIntro);
