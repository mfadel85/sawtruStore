/** @format */

import React, { PureComponent } from "react";
import PropTypes from "prop-types";

import {
  Text,
  TouchableOpacity,
  FlatList,
  View,
  Animated,
  Image,
  Share,
} from "react-native";
import { connect } from "react-redux";
import { Timer, getProductImage, log } from "@app/Omni";
import {
  Button,
  AdMob,
  /*WebView,*/
  ProductSize as ProductAttribute,
  ProductColor,
  ProductRelated,
  Rating,
} from "@components";
import Swiper from "react-native-swiper";
import {
  Styles,
  Languages,
  Color,
  Config,
  Constants,
  Events,
  withTheme,
  Tools,
} from "@common";
import Modal from "react-native-modalbox";
import { find, filter, findIndex } from "lodash";
import * as Animatable from "react-native-animatable";
import AttributesView from "./AttributesView";
import ReviewTab from "./ReviewTab.js";
import styles from "./ProductDetail_Style";
import {decode} from 'html-entities';
import { WebView } from 'react-native-webview';

const PRODUCT_IMAGE_HEIGHT = 300;
const NAVI_HEIGHT = 64;

class Detail extends PureComponent {
  static propTypes = {
    product: PropTypes.any,
    getProductVariations: PropTypes.func,
    productVariations: PropTypes.any,
    onViewCart: PropTypes.func,
    addCartItem: PropTypes.func,
    removeWishListItem: PropTypes.func,
    addWishListItem: PropTypes.func,
    cartItems: PropTypes.any,
    navigation: PropTypes.object,
  };

  constructor(props) {
    super(props);

    this.state = {
      scrollY: new Animated.Value(0),
      tabIndex: 0,
      // selectedAttribute: [],
      // selectedColor: 0,
      selectVariation: null,
      selectedImageId: 0, // is placeholder image
      selectedImage: null,
    };

    this.productInfoHeight = PRODUCT_IMAGE_HEIGHT;
    this.inCartTotal = 0;
    this.isInWishList = false;
  }

  componentDidMount() {
    this.getCartTotal(this.props);
    this.getWishList(this.props);

    this.getProductAttribute(this.props.product);
    this.props.getProductVariations(this.props.product);
  }

  componentWillReceiveProps(nextProps) {
    this.getCartTotal(nextProps, true);
    this.getWishList(nextProps, true);

    // this important to update the variations from the product as the Life cycle is not run again !!!

    if (this.props.product.id != nextProps.product.id) {
      this.props.getProductVariations(nextProps.product);
      this.getProductAttribute(nextProps.product);
      this.forceUpdate();
    }

    if (this.props.productVariations !== nextProps.productVariations) {
      this.updateSelectedVariant(nextProps.productVariations);
    }
  }

  getProductAttribute = (product) => {
    this.productAttributes = product.attributes;
    const defaultAttribute = product.default_attributes;

    if (typeof this.productAttributes !== "undefined") {
      this.productAttributes.map((attribute) => {
        const selectedAttribute = defaultAttribute.find(
          (item) => item.name === attribute.name
        );
        attribute.selectedOption =
          typeof selectedAttribute !== "undefined"
            ? selectedAttribute.option.toLowerCase()
            : "";
      });
    }
  };

  closePhoto = () => {
    this._modalPhoto.close();
  };

  openPhoto = () => {
    this._modalPhoto.open();
  };

  handleClickTab(tabIndex) {
    this.setState({ tabIndex });
    Timer.setTimeout(() => this.state.scrollY.setValue(0), 50);
  }

  getColor = (value) => {
    const color = value.toLowerCase();
    if (typeof Color.attributes[color] !== "undefined") {
      return Color.attributes[color];
    }
    return "#333";
  };

  share = () => {
    Share.share({
      message: this.props.product.description.replace(/(<([^>]+)>)/gi, ""),
      url: this.props.product.permalink,
      title: this.props.product.name,
    });
  };

  addToCart = (go = false) => {
    const { addCartItem, product, onViewCart } = this.props;

    if (this.inCartTotal < Constants.LimitAddToCart) {
      addCartItem(product, this.state.selectVariation);
    } else {
      alert(Languages.ProductLimitWaring);
    }
    if (go) onViewCart();
  };

  addToWishList = (isAddWishList) => {
    if (isAddWishList) {
      this.props.removeWishListItem(this.props.product);
    } else this.props.addWishListItem(this.props.product);
  };

  getCartTotal = (props, check = false) => {
    const { cartItems } = props;

    if (cartItems != null) {
      if (check === true && props.cartItems === this.props.cartItems) {
        return;
      }

      this.inCartTotal = cartItems.reduce((accumulator, currentValue) => {
        if (currentValue.product.id == this.props.product.id) {
          return accumulator + currentValue.quantity;
        }
        return 0;
      }, 0);

      const sum = cartItems.reduce(
        (accumulator, currentValue) => accumulator + currentValue.quantity,
        0
      );
      const params = this.props.navigation.state.params;
      params.cartTotal = sum;
      this.props.navigation.setParams(params);
    }
  };

  getWishList = (props, check = false) => {
    const { product, navigation, wishListItems } = props;

    if (props.hasOwnProperty("wishListItems")) {
      if (check == true && props.wishListItems == this.props.wishListItems) {
        return;
      }
      this.isInWishList =
        find(props.wishListItems, (item) => item.product.id == product.id) !=
        "undefined";

      const sum = wishListItems.length;
      const params = navigation.state.params;
      params.wistListTotal = sum;
      this.props.navigation.setParams(params);
    }
  };

  onSelectAttribute = (attributeName, option) => {
    const selectedAttribute = this.productAttributes.find(
      (item) => item.name === attributeName
    );
    selectedAttribute.selectedOption = option.toLowerCase();

    this.updateSelectedVariant(this.props.productVariations);
  };

  updateSelectedVariant = (productVariations) => {
    let hasAttribute = false;
    const defaultVariant =
      productVariations && productVariations.length
        ? productVariations[0]
        : null;
    // filter selectedOption null or don't have variation
    const selectedAttribute = filter(
      this.productAttributes,
      (item) =>
        (item.selectedOption && item.selectedOption !== "") || item.variation
    );
    let selectedImage =
      (defaultVariant && (defaultVariant.image && defaultVariant.image.src)) ||
      "";
    let selectedImageId = 0;

    if (productVariations && productVariations.length) {
      productVariations.map((variant) => {
        let matchCount = 0;
        selectedAttribute.map((selectAttribute) => {
          const isMatch = find(
            variant.attributes,
            (item) =>
              item.name.toUpperCase() === selectAttribute.name.toUpperCase() &&
              item.option.toUpperCase() ===
              selectAttribute.selectedOption.toUpperCase()
          );
          if (isMatch !== undefined) {
            matchCount += 1;
          }
        });

        if (matchCount === selectedAttribute.length) {
          hasAttribute = true;
          selectedImage = (variant.image && variant.image.src) || "";
          selectedImageId = variant.image.id;
          this.setState({
            selectVariation: variant,
            selectedImage,
            selectedImageId,
          });
        }
      });
    }

    // set default variant
    if (!hasAttribute && defaultVariant) {
      this.setState({
        selectVariation: defaultVariant,
        selectedImage,
        selectedImageId,
      });
    }

    this.forceUpdate();
  };

  /**
   * render Image top
   */
  _renderImages = () => {
    const { selectedImage, selectedImageId } = this.state;
    const imageScale = this.state.scrollY.interpolate({
      inputRange: [-300, 0, NAVI_HEIGHT, this.productInfoHeight / 2],
      outputRange: [2, 1, 1, 0.7],
      extrapolate: "clamp",
    });

    // set variant image and only show when do not placeholder image
    if (selectedImage && selectedImageId !== 0)
      return (
        <View
          style={{
            // height: PRODUCT_IMAGE_HEIGHT,
            width: Constants.Window.width,
          }}>
          <TouchableOpacity activeOpacity={1} onPress={this.openPhoto}>
            <Animated.Image
              source={{
                uri: getProductImage(this.state.selectedImage, Styles.width),
              }}
              style={[
                styles.imageProduct,
                { transform: [{ scale: imageScale }] },
              ]}
              resizeMode="contain"
            />
          </TouchableOpacity>
        </View>
      );

    return (
      <FlatList
        contentContainerStyle={{ paddingLeft: Styles.spaceLayout }}
        ref={(comp) => (this._photos = comp)}
        data={this.props.product.images}
        renderItem={({ item, index }) => {
          return (
            <TouchableOpacity
              activeOpacity={1}
              key={index}
              onPress={this.openPhoto}>
              <Animated.Image
                source={{ uri: getProductImage(item.src, Styles.width) }}
                style={[
                  styles.imageProduct,
                  { transform: [{ scale: imageScale }] },
                ]}
                resizeMode="contain"
              />
            </TouchableOpacity>
          );
        }}
        keyExtractor={(item, index) => item.id || index.toString()}
        showsHorizontalScrollIndicator={false}
        horizontal
        pagingEnabled
      />
    );
  };

  /**
   * Render tabview detail
   */
  renderTabView = () => {
    const {
      theme: {
        colors: { background, text, lineColor },
      },
    } = this.props;

    return (
      <View style={[styles.tabView, { backgroundColor: background }]}>
        <View
          style={[
            styles.tabButton,
            { backgroundColor: lineColor },
            { borderTopColor: lineColor },
            { borderBottomColor: lineColor },
            Constants.RTL && { flexDirection: "row-reverse" },
          ]}>
          <View style={[styles.tabItem, { backgroundColor: lineColor }]}>
          <Text>{Languages.AdditionalInformation}</Text>
          <View style={[styles.description, { backgroundColor: lineColor }]}>
            <Text>{this.props.product.description}</Text>
            
            {/*
            <WebView
              originWhitelist={['*']}
              source={{ html: '<p>Here I am</p>' }}
              /><WebView
              textColor={text}
              html={`<p>${this.props.product.description}</p>`}
            />*/}
          </View>
          </View>
   
        </View>

      </View>
    );

  }
  _renderTabView = () => {
    const {
      theme: {
        colors: { background, text, lineColor },
      },
    } = this.props;
    console.log('desc is ',decode(this.props.product.description))
    return (
      <View style={[styles.tabView, { backgroundColor: background }]}>
        <View
          style={[
            styles.tabButton,
            { backgroundColor: lineColor },
            { borderTopColor: lineColor },
            { borderBottomColor: lineColor },
            Constants.RTL && { flexDirection: "row-reverse" },
          ]}>
          <View style={[styles.tabItem, { backgroundColor: lineColor }]}>
            <Button
              type="tab"
              textStyle={[styles.textTab, { color: text }]}
              selectedStyle={{ color: text }}
              text={Languages.AdditionalInformation}
              onPress={() => this.handleClickTab(0)}
              selected={this.state.tabIndex == 0}
            />
          </View>
          <View style={[styles.tabItem, { backgroundColor: lineColor }]}>
            <Button
              type="tab"
              textStyle={[styles.textTab, { color: text }]}
              selectedStyle={{ color: text }}
              text={Languages.ProductFeatures}
              onPress={() => this.handleClickTab(1)}
              selected={this.state.tabIndex == 1}
            />
          </View>
          <View style={[styles.tabItem, { backgroundColor: lineColor }]}>
            <Button
              type="tab"
              textStyle={[styles.textTab, { color: text }]}
              selectedStyle={{ color: text }}
              text={Languages.ProductReviews}
              onPress={() => this.handleClickTab(2)}
              selected={this.state.tabIndex == 2}
            />
          </View>
        </View>
        {this.state.tabIndex === 0 && (
          <WebView
            source={{ html: decode(this.props.product.description) }}
          />
                    
        )}
        {this.state.tabIndex === 1 && (
          <AttributesView attributes={this.props.product.attributes} />
        )}
        {this.state.tabIndex === 2 && (
          <ReviewTab product={this.props.product} />
        )}
      </View>
    );
  };

  _writeReview = () => {
    const { product, userData, onLogin } = this.props;
    if (userData) {
      Events.openModalReview(product);
    } else {
      onLogin();
    }
  };

  render() {
    const { selectVariation } = this.state;
    const {
      wishListItems,
      onViewProductScreen,
      product,
      cartItems,
      theme: {
        colors: { background, text, lineColor },
        dark,
      },
    } = this.props;

    const isAddToCart = !!(
      cartItems &&
      cartItems.filter((item) => item.product.id === product.id).length > 0
    );
    const isAddWishList =
      wishListItems.filter((item) => item.product.id === product.id).length > 0;
    const procductPriceIncludedTax = Tools.getPriceIncluedTaxAmount(
      product,
      selectVariation
    );
    const productRegularPrice = Tools.getCurrecyFormatted(
      selectVariation ? selectVariation.regular_price : product.regular_price
    );
    const isOnSale = selectVariation
      ? selectVariation.on_sale
      : product.on_sale;

    const renderButtons = () => (
      <View
        style={[
          styles.bottomView,
          dark && { borderTopColor: lineColor },
          Constants.RTL && { flexDirection: "row-reverse" },
        ]}>
        <View
          style={[
            styles.buttonContainer,
            dark && { backgroundColor: lineColor },
          ]}>
          <Button
            type="image"
            source={require("@images/icons/icon-share.png")}
            imageStyle={[styles.imageButton, { tintColor: text }]}
            buttonStyle={styles.buttonStyle}
            onPress={this.share}
          />
          <Button
            type="image"
            isAddWishList={isAddWishList}
            source={require("@images/icons/icon-love.png")}
            imageStyle={[styles.imageButton, { tintColor: text }]}
            buttonStyle={styles.buttonStyle}
            onPress={() => this.addToWishList(isAddWishList)}
          />
          {!Config.HideCartAndCheckout && (
            <Button
              type="image"
              isAddToCart={isAddToCart}
              source={require("@images/icons/icon-cart.png")}
              imageStyle={[styles.imageButton, { tintColor: text }]}
              disabled={!product.in_stock}
              buttonStyle={styles.buttonStyle}
              onPress={() => product.in_stock && this.addToCart(true)}
            />
          )}
        </View>
        {!Config.HideCartAndCheckout && (
          <Button
            text={product.in_stock ? Languages.BUYNOW : Languages.OutOfStock}
            style={[styles.btnBuy, !product.in_stock && styles.outOfStock]}
            textStyle={styles.btnBuyText}
            disabled={!product.in_stock}
            onPress={() => {
              product.in_stock && this.addToCart(true);
            }}
          />
        )}
      </View>
    );

    const renderRating = () => {
      return (
        <View style={styles.price_wrapper(background)}>
          <Rating rating={Number(product.average_rating)} size={19} />

          <Text style={[styles.textRating, { color: text }]}>
            {`(${product.rating_count})`}
          </Text>

          <TouchableOpacity onPress={this._writeReview}>
            <Text style={[styles.textRating, { color: text }]}>
              {Languages.writeReview}
            </Text>
          </TouchableOpacity>
        </View>
      );
    };

    const renderTitle = () => (
      <View style={{ justifyContent: "center", marginTop: 6, marginBottom: 8 }}>
        <Text style={[styles.productName, { color: text }]}>
          {product.name}
        </Text>
        <View
          style={{
            flexDirection: "row",
            justifyContent: "center",
            marginTop: 2,
            marginBottom: 4,
          }}>
          <Animatable.Text
            animation="fadeInDown"
            style={[styles.productPrice, { color: text }]}>
            {procductPriceIncludedTax}
          </Animatable.Text>
          {isOnSale && (
            <Animatable.Text
              animation="fadeInDown"
              style={[styles.sale_price, { color: text }]}>
              {productRegularPrice}
            </Animatable.Text>
          )}
        </View>
      </View>
    );

    const renderAttributes = () => (
      <View>
        {typeof this.productAttributes !== "undefined" &&
          this.productAttributes.map((attribute, attrIndex) => (
            <View
              key={`attr${attrIndex}`}
              style={[
                styles.productSizeContainer,
                Constants.RTL && { flexDirection: "row-reverse" },
              ]}>
              {attribute.name !== Constants.productAttributeColor &&
                attribute.options.map((option, index) => (
                  <ProductAttribute
                    key={index}
                    text={option}
                    style={styles.productSize}
                    onPress={() =>
                      this.onSelectAttribute(attribute.name, option)
                    }
                    selected={
                      attribute.selectedOption.toLowerCase() ===
                      option.toLowerCase()
                    }
                  />
                ))}
            </View>
          ))}
      </View>
    );

    const renderProductColor = () => {
      if (typeof this.productAttributes === "undefined") {
        return;
      }

      const productColor = this.productAttributes.find(
        (item) => item.name === Constants.productAttributeColor
      );
      if (productColor) {
        const translateY = this.state.scrollY.interpolate({
          inputRange: [0, PRODUCT_IMAGE_HEIGHT / 2, PRODUCT_IMAGE_HEIGHT],
          outputRange: [0, -PRODUCT_IMAGE_HEIGHT / 3, -PRODUCT_IMAGE_HEIGHT],
          extrapolate: "clamp",
        });

        return (
          <Animated.View
            style={[
              styles.productColorContainer,
              { transform: [{ translateY }] },
            ]}>
            {productColor.options.map((option, index) => (
              <ProductColor
                key={index}
                color={this.getColor(option)}
                onPress={() =>
                  this.onSelectAttribute(
                    Constants.productAttributeColor,
                    option
                  )
                }
                selected={
                  productColor.selectedOption.toLowerCase() ===
                  option.toLowerCase()
                }
              />
            ))}
          </Animated.View>
        );
      }
    };

    const renderProductRelated = () => (
      <ProductRelated
        onViewProductScreen={(product) => {
          this.list.getNode().scrollTo({ x: 0, y: 0, animated: true });
          onViewProductScreen(product);
        }}
        product={product}
      // tags={product.related_ids}
      />
    );
{/* to be in line 682
 onScroll={(event) => {
            this.state.scrollY.setValue(event.nativeEvent.contentOffset.y);
          }}
*/}
    return (
      
      <View style={[styles.container, { backgroundColor: background }]}>
        <Animated.ScrollView
          ref={(c) => (this.list = c)}
          overScrollMode="never"
          style={styles.listContainer}
          scrollEventThrottle={1}
          onScroll={(event) => {
            this.state.scrollY.setValue(event.nativeEvent.contentOffset.y);
          }}
         >
          <View
            style={[styles.productInfo, { backgroundColor: background }]}
            onLayout={(event) =>
              (this.productInfoHeight = event.nativeEvent.layout.height)
            }>

            {this._renderImages()}
            {renderAttributes()}
            {renderTitle()}
            {renderRating()}

          </View>
         {this._renderTabView()}
           {renderProductRelated()}
           
          <AdMob />
        </Animated.ScrollView>
        {renderProductColor()}

        {renderButtons()}

        <Modal
          ref={(com) => (this._modalPhoto = com)}
          swipeToClose={false}
          animationDuration={200}
          style={styles.modalBoxWrap}>
          <Swiper
            height={Constants.Window.height - 40}
            activeDotStyle={styles.dotActive}
            removeClippedSubviews={false}
            dotStyle={styles.dot}
            paginationStyle={{ zIndex: 9999, bottom: -15 }}>
            {product.images.map((image, index) => (
              <Image
                key={index.toString()}
                source={{ uri: getProductImage(image.src, Styles.width) }}
                style={styles.imageProductFull}
              />
            ))}
          </Swiper>

          <TouchableOpacity style={styles.iconZoom} onPress={this.closePhoto}>
            <Text style={styles.textClose}>{Languages.close}</Text>
          </TouchableOpacity>
        </Modal>
      </View>
    );
  }
}

const mapStateToProps = (state) => {
  return {
    cartItems: state.carts.cartItems,
    wishListItems: state.wishList.wishListItems,
    productVariations: state.products.productVariations,
    userData: state.user.user,
  };
};

function mergeProps(stateProps, dispatchProps, ownProps) {
  const { dispatch } = dispatchProps;
  const CartRedux = require("@redux/CartRedux");
  const WishListRedux = require("@redux/WishListRedux");
  const ProductRedux = require("@redux/ProductRedux");
  return {
    ...ownProps,
    ...stateProps,
    addCartItem: (product, variation) => {
      CartRedux.actions.addCartItem(dispatch, product, variation);
    },
    addWishListItem: (product) => {
      WishListRedux.actions.addWishListItem(dispatch, product);
    },
    removeWishListItem: (product) => {
      WishListRedux.actions.removeWishListItem(dispatch, product);
    },
    getProductVariations: (product) => {
      ProductRedux.actions.getProductVariations(dispatch, product);
    },
  };
}

export default withTheme(
  connect(
    mapStateToProps,
    undefined,
    mergeProps
  )(Detail)
);
