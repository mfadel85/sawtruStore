/** @format */

import React, { PureComponent } from "react";

import { Color, Styles, withTheme } from "@common";

import { Categories } from "@containers";
import { Logo, Menu, EmptyView } from "./IconNav";

@withTheme
export default class CategoriesScreen extends PureComponent {
  static navigationOptions = ({ navigation }) => {
    const headerStyle = navigation.getParam(
      "headerStyle",
      Styles.Common.toolbar()
    );
    const dark = navigation.getParam("dark", false);

    return {
      headerTitle: Logo(),
      headerLeft: Menu(dark),
      headerRight: EmptyView(),

      headerTintColor: Color.headerTintColor,
      headerStyle,
      headerTitleStyle: Styles.Common.headerStyle,
    };
  };

  componentWillMount() {
    const {
      theme: {
        colors: { background },
        dark,
      },
    } = this.props;

    this.props.navigation.setParams({
      headerStyle: Styles.Common.toolbar(background, dark),
      dark
    });
  }

  componentWillReceiveProps(nextProps) {
    if (this.props.theme.dark !== nextProps.theme.dark) {
      const {
        theme: {
          colors: { background },
          dark,
        },
      } = nextProps;
      this.props.navigation.setParams({
        headerStyle: Styles.Common.toolbar(background, dark),
        dark
      });
    }
  }

  render() {
    const { navigate } = this.props.navigation;
    return (
      <Categories
        onViewProductScreen={(item) => navigate("DetailScreen", item)}
        onViewCategory={(item) => {
          navigate("CategoryScreen", item);
        }}
      />
    );
  }
}
