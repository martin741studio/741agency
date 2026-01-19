---
name: handling-errors
description: Master error handling patterns across languages including exceptions, Result types, error propagation, and graceful degradation to build resilient applications. Use when implementing error handling, designing APIs, or improving application reliability.
---

# Error Handling Patterns

Build resilient applications with robust error handling strategies that gracefully handle failures and provide excellent debugging experiences.

## When to Use This Skill
- Implementing error handling in new features
- Designing error-resilient APIs
- Debugging production issues
- Improving application reliability
- Creating better error messages for users and developers
- Implementing retry and circuit breaker patterns
- Handling async/concurrent errors
- Building fault-tolerant distributed systems

## Workflow

1.  **Identify the Context**: Are you designing a new API, troubleshooting a bug, or improving resilience?
2.  **Choose the Strategy**:
    - **Recoverable?** Use Result types or specific exceptions.
    - **Unrecoverable?** Crash early or bubble up to a global handler.
    - **Distributed?** Consider Circuit Breakers or Retries.
3.  **Consult Reference Patterns**:
    - [Python Patterns](resources/patterns-python.md)
    - [TypeScript/JavaScript Patterns](resources/patterns-typescript.md)
    - [Rust Patterns](resources/patterns-rust.md)
    - [Go Patterns](resources/patterns-go.md)
    - [Universal Patterns (Circuit Breaker, Aggregation)](resources/patterns-universal.md)
4.  **Verify & Refine**:
    - Use [Best Practices](resources/best-practices.md) to check your implementation.
    - Ensure errors are logged with context.

## Core Concepts

### 1. Error Handling Philosophies
**Exceptions vs Result Types:**
- **Exceptions**: Traditional try-catch, disrupts control flow. Use for unexpected errors, exceptional conditions.
- **Result Types**: Explicit success/failure, functional approach. Use for expected errors, validation failures.
- **Error Codes**: C-style, requires discipline.
- **Option/Maybe Types**: For nullable values.
- **Panics/Crashes**: Unrecoverable errors, programming bugs.

### 2. Error Categories
**Recoverable Errors:**
- Network timeouts
- Missing files
- Invalid user input
- API rate limits

**Unrecoverable Errors:**
- Out of memory
- Stack overflow
- Programming bugs (null pointer, etc.)

## Common Pitfalls
- **Catching Too Broadly**: `except Exception` hides bugs.
- **Empty Catch Blocks**: Silently swallowing errors.
- **Logging and Re-throwing**: Creates duplicate log entries.
- **Not Cleaning Up**: Forgetting to close files, connections.
- **Poor Error Messages**: "Error occurred" is not helpful.
- **Returning Error Codes**: Use exceptions or Result types.
- **Ignoring Async Errors**: Unhandled promise rejections.
