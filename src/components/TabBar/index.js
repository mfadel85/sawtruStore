/** @format */

import React, { PureComponent } from "react";
import PropTypes from "prop-types";
import {
  View,
  Platform,
  StyleSheet,
  TouchableWithoutFeedback,
} from "react-native";
import { StackActions } from "react-navigation";
import * as Animatable from "react-native-animatable";
import { connect } from "react-redux";

import { log } from "@app/Omni";
import { Device, withTheme } from "@common";

const styles = StyleSheet.create({
  tabbar: {
    height: Device.isIphoneX ? 60 : 49,
    flexDirection: "row",
    justifyContent: "center",
    alignItems: "center",
    borderTopWidth: 1,
    borderTopColor: "#eee",
    backgroundColor: "#fff",
  },
  tab: {
    alignSelf: "stretch",
    flex: 1,
    alignItems: "center",
    ...Platform.select({
      ios: {
        justifyContent: Device.isIphoneX ? "flex-start" : "center",
        paddingTop: Device.isIphoneX ? 12 : 0,
      },
      android: {
        justifyContent: "center",
      },
    }),
  },
});

@withTheme
class TabBar extends PureComponent {
  onPress = (index, route) => {
    this.refs[`tabItem${index}`].flipInY(900);

    // back to main screen when is staying child route

    if (route.routes && route.routes.length > 1 && index !== 1) {
      log(route);
      this.props.navigation.dispatch(
        StackActions.popToTop({ key: route.key, immediate: true })
      );
    } else {
      this.props.navigation.navigate(route.key);
    }
  };

  render() {
    const {
      navigation,
      renderIcon,
      activeTintColor,
      inactiveTintColor,
      theme: {
        colors: { background },
      },
    } = this.props;

    const { routes } = navigation.state;

    const ignoreScreen = [
      "DetailScreen",
      "SearchScreen",
      "Detail",
      "NewsScreen",
      "LoginScreen",
      "SignUpScreen",
      "CustomPage",
      "CategoryDetail",
      "SettingScreen",
      "WishListScreen",
      "LoginStack",
    ];

    return (
      <View
        style={[
          styles.tabbar,
          { backgroundColor: background, borderTopColor: background },
        ]}>
        {routes &&
          routes.map((route, index) => {
            const focused = index === navigation.state.index;
            const tintColor = focused ? activeTintColor : inactiveTintColor;

            if (ignoreScreen.indexOf(route.key) > -1) {
              return <View key={route.key} />;
            }

            if (this.props.user === null && route.key === "MyOrders") {
              return <View key={route.key} />;
            }

            return (
              <TouchableWithoutFeedback
                key={route.key}
                style={styles.tab}
                onPress={() => this.onPress(index, route)}>
                <Animatable.View ref={`tabItem${index}`} style={styles.tab}>
                  {renderIcon({
                    route,
                    index,
                    focused,
                    tintColor,
                  })}
                </Animatable.View>
              </TouchableWithoutFeedback>
            );
          })}
      </View>
    );
  }
}

TabBar.propTypes = {
  user: PropTypes.object,
  navigation: PropTypes.object,
  renderIcon: PropTypes.any,
  activeTintColor: PropTypes.string,
  inactiveTintColor: PropTypes.string,
  jumpTo: PropTypes.func,
};
const mapStateToProps = ({ user }) => ({ user: user.user });
export default connect(mapStateToProps)(TabBar);
