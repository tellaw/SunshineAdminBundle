var React = require('react');
var ReactDOM = require('react-dom');

var options = {
    thumbnailData:  [{
        title: 'Show Courses',
        number: 120,
        header: 'Learn React',
        description: 'React is a fantastic new front end library for rendering web pages. React is a fantastic new front end library for rendering web pages.',
        imageUrl: 'https://raw.githubusercontent.com/wiki/facebook/react/react-logo-1000-transparent.png'
    },{
        title: 'Show Courses',
        number: 25,
        header: 'Learn Gulp',
        description: 'Gulp will speed up your development workflow.  Gulp will speed up your development workflow.  Gulp will speed up your development workflow.',
        imageUrl: 'http://brunch.io/images/others/gulp.png'
    }]
};

// Test
ReactDOM.render(
    <h1>Hello, world TOTO !</h1>,
    document.querySelector('#reactContent')
);
