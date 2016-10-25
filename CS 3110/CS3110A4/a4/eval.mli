open Ast

(**
 * values are expressions that don't evaluate any further.  With the exception
 * of VClosure and VError, the value constructors are analogous to the Ast.expr
 * constructors.
 *   - VClosure ("x", e, env) represents a closure whose code part
 *     is fun x -> e and whose environment part is env
 *   - VError s is used to indicate that evaluation failed for some reason;
 *     the string is unspecified
 *)
type value =
  | VUnit | VInt of int | VBool of bool | VString of string
  | VClosure of var * expr * environment
  | VVariant of constructor * value
  | VPair of value * value
  | VError of string

(**
 * An environment is a mapping of variables to values,
 * represented by an association list.  As usual for
 * association lists, if a key is bound more than once
 * in the list, it is the left-most binding (i.e.,
 * closest to the head of the list) that determines
 * the value.
 *)
and environment = (var * value ref) list

val format_value    : Format.formatter -> value -> unit
val print_value     : value -> unit
val string_of_value : value -> string

(** [eval] implements the big-step environment model of evaluation.
 *
 *  [eval env e] returns [v] where
 *  <env,e> ==> v (using the notation from lecture)
 *
 * This function should return VError if the expression is not well formed (e.g.
 * if it contains an unbound variable or a match expression doesn't contain a
 * matching pattern)
 *)
val eval : environment -> expr -> value


