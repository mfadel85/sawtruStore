/**
 * Created by InspireUI on 27/02/2017.
 *
 * @format
 */

import React, { PureComponent } from "react";
import PropTypes from "prop-types";
import { View, ScrollView, Image, I18nManager } from "react-native";
import { connect } from "react-redux";

import { Accordion, Empty, Text } from "@components";
import { toast } from "@app/Omni";
import {
  Color,
  Icons,
  Config,
  Languages,
  Tools,
  withTheme,
  Theme,
} from "@common";
import { DrawerButton, DrawerButtonChild } from "../DrawerButton";
import styles from "./styles";

class DrawerMultiChild extends PureComponent {
  static propTypes = {
    backgroundMenu: PropTypes.string,
    colorTextMenu: PropTypes.string,
    userProfile: PropTypes.object,
    fetchCategories: PropTypes.func.isRequired,
    goToScreen: PropTypes.func.isRequired,
    setSelectedCategory: PropTypes.func.isRequired,
    categories: PropTypes.any,
  };

  static defaultProps = {
    backgroundMenu: "#FFF",
  };

  constructor(props) {
    super(props);

    const { user } = props.userProfile;
    // Config Menu
    if (user) {
      this.buttonList = [
        ...Config.menu.listMenu,
        ...Config.menu.listMenuLogged,
      ];
    } else {
      this.buttonList = [
        ...Config.menu.listMenu,
        ...Config.menu.listMenuUnlogged,
      ];
    }

    this.state = {};
  }

  componentDidMount() {
    const { fetchCategories } = this.props;
    fetchCategories();
  }

  /**
   * Update when logged in
   */
  componentWillReceiveProps(props) {
    const { userProfile } = props;
    const { error } = props.categories;
    if (error) toast(error);

    if (userProfile && userProfile.user) {
      this.buttonList = [
        ...Config.menu.listMenu,
        ...Config.menu.listMenuLogged,
      ];
    }
  }

  /**
   * Render header of accordion menu
   */
  _renderHeader = (section, index, isActive) => {
    const { colorTextMenu } = this.props;

    return (
      <DrawerButtonChild
        iconRight={isActive ? Icons.Ionicons.Remove : Icons.Ionicons.Add}
        text={Tools.getDescription(section.name)}
        uppercase
        key={index}
        colorText={colorTextMenu}
        {...section}
      />
    );
  };

  /**
   * Render content of accordion menu
   */
  _renderContent = (section) => {
    const { categories, selectedCategory, colorTextMenu } = this.props;
    const subCategories = this._getCategories(categories, section);

    return (
      <View>
        <View key={-1} style={{ marginLeft: 20 }}>
          <DrawerButton
            {...section}
            onPress={() => this._handlePress(section, section)}
            text={Languages.seeAll}
            textStyle={styles.textItem}
            colorText={colorTextMenu}
          />
        </View>
        {subCategories.map((cate, index) => {
          return (
            <View key={index} style={{ marginLeft: 20 }}>
              <DrawerButton
                {...section}
                onPress={() => this._handlePress(cate, section)}
                text={cate.name}
                textStyle={styles.textItem}
                colorText={colorTextMenu}
                isActive={
                  selectedCategory ? selectedCategory.id === cate.id : false
                }
              />
            </View>
          );
        })}
      </View>
    );
  };

  _renderRowCategories = () => {
    const { categories, colorTextMenu } = this.props;
    const mainCategories = this._getCategories(categories);

    if (
      categories.error ||
      !categories ||
      (categories && categories.list === 0)
    ) {
      return <Empty />;
    }

    return (
      <View>
        {mainCategories && mainCategories.length ? (
          <View>
            <View style={styles.headerCategory}>
              <Text
                style={[
                  styles.textHeaderCategory,
                  {
                    color: colorTextMenu,
                  },
                ]}>
                {Languages.Category && Languages.Category.toUpperCase()}
              </Text>
            </View>
            <Accordion
              underlayColor={Color.DirtyBackground}
              sections={mainCategories}
              renderHeader={this._renderHeader}
              renderContent={this._renderContent}
            />
          </View>
        ) : null}
      </View>
    );
  };

  _getCategories = (categories, section) => {
    if (categories && categories.list.length) {
      return categories.list.filter((cate, index) => {
        if (section) {
          return cate.parent === section.id; // check is sub category
        }
        return cate.parent === 0;
      });
    }

    return [];
  };

  _handlePress = (item, section) => {
    const { goToScreen, setSelectedCategory, categories } = this.props;

    if (section) {
      const params = {
        ...item,
        mainCategory: section,
      };
      setSelectedCategory(params);
      goToScreen("CategoryDetail", params, false);
    } else {
      goToScreen(item.routeName, item.params, false);
    }
  };

  render() {
    const { userProfile, backgroundMenu, colorTextMenu } = this.props;
    const user = userProfile.user;
    const avatar = Tools.getAvatar(user);
    const name = Tools.getName(user);

    return (
      <View
        style={[
          styles.container,
          {
            backgroundColor: backgroundMenu,
          },
        ]}>
        <View
          style={[
            styles.avatarBackground,
            {
              backgroundColor: backgroundMenu,
            },
          ]}>
          <Image
            source={avatar}
            style={[styles.avatar, I18nManager.isRTL && { left: -20 }]}
          />

          <View style={styles.textContainer}>
            <Text
              style={[
                styles.fullName,
                {
                  color: colorTextMenu,
                },
              ]}>
              {name}
            </Text>
            <Text
              style={[
                styles.email,
                {
                  color: colorTextMenu,
                },
              ]}>
              {user ? user.email : ""}
            </Text>
          </View>
        </View>
        <ScrollView>
          {this.buttonList.map((item, index) => (
            <DrawerButton
              {...item}
              key={index}
              onPress={() => this._handlePress(item)}
              icon={null}
              uppercase
              colorText={colorTextMenu}
              textStyle={styles.textItem}
            />
          ))}
          {this._renderRowCategories()}
        </ScrollView>
      </View>
    );
  }
}

const mapStateToProps = ({ user, categories, netInfo, language }) => ({
  userProfile: user,
  netInfo,
  categories,
  selectedCategory: categories.selectedCategory,
  language: language.lang,
});

function mergeProps(stateProps, dispatchProps, ownProps) {
  const { netInfo } = stateProps;
  const { dispatch } = dispatchProps;
  const { actions } = require("@redux/CategoryRedux");

  return {
    ...ownProps,
    ...stateProps,
    setSelectedCategory: (category) =>
      dispatch(actions.setSelectedCategory(category)),
    fetchCategories: () => {
      if (!netInfo.isConnected) return toast(Languages.noConnection);
      actions.fetchCategories(dispatch);
    },
  };
}

export default connect(
  mapStateToProps,
  null,
  mergeProps
)(withTheme(DrawerMultiChild));
