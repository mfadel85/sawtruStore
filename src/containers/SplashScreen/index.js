/**
 * Created by InspireUI on 23/02/2017.
 *
 * @format
 */

import React, { PureComponent } from "react";
import { Image, StyleSheet, Platform, UIManager } from "react-native";
import { NavigationActions } from "react-navigation";
import { connect } from "react-redux";
import { Images, Styles } from "@common";
import { Timer } from "@app/Omni";
import { SafeAreaView } from "@components";

const minDisplayTime = 1000;

class SplashScreen extends PureComponent {
  constructor(props) {
    super(props);
    this.state = {};

    this.prepareData = this.prepareData.bind(this);

    if (Platform.OS === "android") {
      // noinspection JSUnresolvedFunction
      UIManager.setLayoutAnimationEnabledExperimental(true); // enable Animation on Android
    }
  }

  componentDidMount() {
    Timer.setTimeout(this.prepareData, minDisplayTime);
  }

  render() {
    return (
      <SafeAreaView>
        <Image
          source={Images.splashScreen}
          style={styles.image}
          resizeMode="cover"
        />
      </SafeAreaView>
    );
  }

  /**
   * All necessary task like: pre-load data from server, checking local resource, configure settings,...
   * Should be done in this function and redirect to other screen when complete.
   */
  prepareData() {
    // noinspection Eslint
    const { user, netInfo, navigation } = this.props;
    if (netInfo.isConnected) {
      // Task that only work in online mode go here...
    } else {
      // Task that only work in offline mode go here...
    }

    const resetData = {
      index: 0,
      actions: [NavigationActions.navigate({ routeName: "HomeScreen" })],
    };
    navigation.dispatch(NavigationActions.reset(resetData));
  }
}

const styles = StyleSheet.create({
  image: {
    height: Styles.height,
    width: Styles.width,
  },
});

SplashScreen.navigationOptions = {
  header: null,
};

const mapStateToProps = ({ netInfo, user }) => ({ netInfo, user });
export default connect(mapStateToProps)(SplashScreen);
