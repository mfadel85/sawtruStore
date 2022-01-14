/** @format */

import React, { PureComponent } from "react";

import { Images, Styles, withTheme } from "@common";
import { Filters } from "@containers";
import { Back } from "./IconNav";

@withTheme
export default class FiltersScreen extends PureComponent {
  static navigationOptions = ({ navigation }) => {
    const dark = navigation.getParam("dark", false);

    return {
      headerLeft: Back(navigation, Images.icons.backs, dark),
      headerStyle: Styles.Common.toolbarFloat,
      headerTransparent: true,
    };
  };

  componentWillMount() {
    const {
      theme: { dark },
    } = this.props;

    this.props.navigation.setParams({
      dark,
    });
  }

  componentWillReceiveProps(nextProps) {
    if (this.props.theme.dark !== nextProps.theme.dark) {
      const {
        theme: { dark },
      } = nextProps;
      this.props.navigation.setParams({
        dark,
      });
    }
  }

  render() {
    const { navigation } = this.props;

    return (
      <Filters navigation={navigation} onBack={() => navigation.goBack()} />
    );
  }
}
