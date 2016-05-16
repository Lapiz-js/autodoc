## AutoDoc

A simple documentation generator.
```javascript
// > SomeFunction(arg1, arg2)
// > SomeFunction(different, signature, option)
// Lines
// of
// documentation
/* >
example();
code = 0;
*/
// more docs
SomeFunction(){
  ...
}
```
The function, method or property on the first line will be used as the header.
So in the example above "SomeFunction" will be used as the header. A code block
will be generated with all the lines that start with >. 
