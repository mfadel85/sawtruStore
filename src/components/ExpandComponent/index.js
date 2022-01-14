/* @flow */

import * as React from 'react';
import { View, Animated } from 'react-native';
import PropTypes from 'prop-types';

class ExpendComponent extends React.Component<Props> {
  constructor(props) {
    super(props)
    this.state = {
      animation: new Animated.Value(0),
      minHeight: 0,
      maxHeight: 0
    }
    this.expanded = true
  }


  static defaultProps = {
    onChangeStatus: () => { }
  }

  render() {
    let { contentView, expandView } = this.props

    let { animation, minHeight, maxHeight } = this.state
    const opacity = animation.interpolate({
      inputRange: [minHeight, minHeight + maxHeight],
      outputRange: [0, 1]
    })

    return (
      <Animated.View style={{ height: animation }}>
        <View onLayout={this.setMinHeight} style={{ minHeight: 1 }}>
          {contentView}
        </View>
        {minHeight > 0 && (
          <Animated.View onLayout={this.setMaxHeight} style={{ opacity }}>
            {expandView}
          </Animated.View>
        )}
      </Animated.View>
    );
  }

  setMaxHeight = (event) => {
    if (event.nativeEvent.layout.height > 0) {
      this.setState({ maxHeight: event.nativeEvent.layout.height });
      if (this.expanded) {
        this.setState({ animation: new Animated.Value(this.state.minHeight + event.nativeEvent.layout.height) });
      }
    }
  }

  setMinHeight = (event) => {
    if (this.state.minHeight == 0) {
      this.setState({ minHeight: event.nativeEvent.layout.height, animation: new Animated.Value(event.nativeEvent.layout.height) });
    }
  }

  toggle = () => {
    let { maxHeight, minHeight } = this.state
    let finalValue = this.expanded ? minHeight : maxHeight + minHeight;
    this.expanded = !this.expanded
    this.props.onChangeStatus(this.expanded)
    Animated.timing(
      this.state.animation,
      {
        toValue: finalValue,
        duration: 300,
      }
    ).start();
  }
}

ExpendComponent.propTypes = {
  contentView: PropTypes.element,
  expandView: PropTypes.element
}

export default ExpendComponent;
